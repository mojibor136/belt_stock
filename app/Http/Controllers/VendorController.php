<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorTrx;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vendors = $query->orderBy('id', 'desc')->get();
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'amount'  => 'required|numeric|min:0',
            'email'   => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        $exists = Vendor::where('name', $request->name)
                        ->where('phone', $request->phone)
                        ->where('address', $request->address)
                        ->first();

        if ($exists) {
            return back()->withInput()->with('error', 'একই নাম, ফোন ও ঠিকানার সাথে আরেকজন গ্রাহক ইতিমধ্যেই আছে!');
        }

        try {
            Vendor::create([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'amount'  => $request->amount,
                'email'   => $request->email,
                'address' => $request->address,
                'status'  => 'Credit',
            ]);

            return redirect()->route('vendor.index')->with('success', 'গ্রাহক সফলভাবে তৈরি হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create vendor. Please try again.');
        }
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'      => 'required|exists:vendors,id',
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'amount'  => 'required|numeric|min:0',
            'email'   => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
        ]);

        $exists = Vendor::where('name', $request->name)
                        ->where('phone', $request->phone)
                        ->where('address', $request->address)
                        ->where('id', '!=', $request->id)
                        ->first();

        if ($exists) {
            return back()->withInput()->with('error', 'একই নাম, ফোন ও ঠিকানার সাথে আরেকজন গ্রাহক ইতিমধ্যেই আছে!');
        }

        try {
            $vendor = Vendor::findOrFail($request->id);

            $vendor->update([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'amount'  => $request->amount,
                'email'   => $request->email,
                'address' => $request->address,
            ]);

            return redirect()->route('vendor.index')->with('success', 'গ্রাহক সফলভাবে আপডেট করা হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update vendor. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();
            return redirect()->route('vendor.index')->with('success', 'গ্রাহক সফলভাবে মুছে ফেলা হয়েছে।');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function vendorAnalysis(Request $request, $name, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $startDate = $request->start_date 
            ? Carbon::createFromFormat('d/m/Y', $request->start_date)->startOfDay() 
            : null;
        $endDate = $request->end_date 
            ? Carbon::createFromFormat('d/m/Y', $request->end_date)->endOfDay() 
            : null;

        $query = VendorTrx::where('vendor_id', $id);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $totalInvoice = (clone $query)->where('invoice_status', 'invoice')->sum('invoice');
        $totalPayment = (clone $query)->where('invoice_status', 'payment')->sum('payment');
        $totalTransaction = (clone $query)->sum(DB::raw('invoice + payment'));
        $totalMemo = (clone $query)->where('invoice_status', 'invoice')->count();
        $lastPayment = (clone $query)->where('invoice_status', 'payment')->orderBy('created_at', 'desc')->first();

        return view('vendor.analysis', compact(
            'vendor',
            'totalMemo',
            'lastPayment',
            'totalInvoice',
            'totalPayment',
            'totalTransaction',
            'startDate',
            'endDate'
        ));
    }
}
