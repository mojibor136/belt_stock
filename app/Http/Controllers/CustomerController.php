<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerTrx;
use App\Models\Memo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('transport', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('id', 'desc')->get();

        return view('customer.index', compact('customers'));
    }

    public function getCustomerData($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            return response()->json($customer);
        }

        return response()->json(['error' => 'Customer not found'], 404);
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'transport' => 'nullable|string|max:255',
        ]);

        try {
            $exists = Customer::where('name', $request->name)
                ->where('phone', $request->phone)
                ->where('address', $request->address)
                ->where('email', $request->email)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'একই নাম, ফোন ও ঠিকানার সাথে আরেকজন গ্রাহক ইতিমধ্যেই আছে!');
            }

            Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'amount' => $request->amount,
                'email' => $request->email,
                'address' => $request->address,
                'transport' => $request->transport,
                'status' => 'debit',
            ]);

            return redirect()->route('customer.index')->with('success', 'গ্রাহক সফলভাবে তৈরি হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'transport' => 'nullable|string|max:255',
        ]);

        try {
            $customer = Customer::findOrFail($request->id);

            $exists = Customer::where('name', $request->name)
                ->where('phone', $request->phone)
                ->where('address', $request->address)
                ->where('email', $request->email)
                ->where('id', '!=', $request->id)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'একই নাম, ফোন ও ঠিকানার সাথে আরেকজন গ্রাহক ইতিমধ্যেই আছে!');
            }

            $customer->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'amount' => $request->amount,
                'email' => $request->email,
                'address' => $request->address,
                'transport' => $request->transport,
            ]);

            return redirect()->route('customer.index')->with('success', 'গ্রাহক সফলভাবে আপডেট করা হয়েছে!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update customer. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return redirect()->route('customer.index')->with('success', 'গ্রাহক সফলভাবে মুছে ফেলা হয়েছে।');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function customerSales(Request $request, $id)
    {
        $customer = Customer::find($id);

        $query = Memo::with(['customer', 'items.brand', 'items.group', 'items.sizes'])->where('customer_id', $id)->where('memo_status', 'complete');

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(memo_no) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                    });
            });
        }

        if ($request->filled('created_at')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->created_at)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                \Log::error('Sales memo invalid date', [
                    'input' => $request->created_at,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $memos = $query->latest()->get();

        return view('customer.customerSales', compact('customer', 'memos'));
    }

    public function customerMemo(Request $request, $id)
    {
        $query = Memo::with('customer')->where('customer_id', $id);

        $customer = Customer::find($id);

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(memo_no) LIKE ?', ["%{$search}%"]);
        }
        if ($request->filled('created_at')) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $request->created_at)->format('Y-m-d');
                $query->whereDate('created_at', $date);
            } catch (\Exception $e) {
                Log::error('Pending memo invalid date', ['input' => $request->created_at, 'error' => $e->getMessage()]);
            }
        }
        $memo = $query->latest()->get();

        return view('customer.customerMemo', compact('memo', 'customer'));
    }

    public function customerAnalysis(Request $request, $name, $id)
    {
        $customer = Customer::findOrFail($id);

        $startDate = $request->start_date
            ? Carbon::createFromFormat('d/m/Y', $request->start_date)->startOfDay()
            : null;
        $endDate = $request->end_date
            ? Carbon::createFromFormat('d/m/Y', $request->end_date)->endOfDay()
            : null;

        $query = CustomerTrx::where('customer_id', $id);

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

        $totalProfit = 0;
        $memoQuery = Memo::where('customer_id', $id)
            ->where('memo_status', 'complete')
            ->with('items.sizes');

        if ($startDate && $endDate) {
            $memoQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $memoQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $memoQuery->where('created_at', '<=', $endDate);
        }

        $memos = $memoQuery->get();

        foreach ($memos as $memo) {
            foreach ($memo->items as $item) {
                foreach ($item->sizes as $size) {
                    $sales = $item->inch_rate > 0
                        ? $size->size * $item->inch_rate * $size->quantity
                        : $item->piece_rate * $size->quantity;

                    $cost = $item->cost_inch_rate > 0
                        ? $size->size * $item->cost_inch_rate * $size->quantity
                        : $item->cost_piece_rate * $size->quantity;

                    $totalProfit += ($sales - $cost);
                }
            }
        }

        return view('customer.analysis', compact(
            'customer',
            'totalInvoice',
            'totalPayment',
            'totalTransaction',
            'totalMemo',
            'totalProfit',
            'startDate',
            'endDate'
        ));
    }
}
