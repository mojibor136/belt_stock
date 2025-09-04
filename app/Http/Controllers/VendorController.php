<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

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
}
