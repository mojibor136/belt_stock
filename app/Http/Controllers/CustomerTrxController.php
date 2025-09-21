<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerTrx;
use App\Models\Memo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerTrxController extends Controller
{
    public function payment()
    {
        $customers = Customer::all();

        return view('customer.payment', compact('customers'));
    }

    public function invoice()
    {
        $customers = Customer::all();

        return view('customer.invoice', compact('customers'));
    }

    private function reverseTransaction(Customer $customer, CustomerTrx $trx)
    {
        $customerAmount = (float) $customer->amount;
        $trxAmount = 0;

        if (! empty($trx->invoice)) {
            $trxAmount = (float) $trx->invoice;
            $type = 'invoice';
        } elseif (! empty($trx->payment)) {
            $trxAmount = (float) $trx->payment;
            $type = 'payment';
        } else {
            return;
        }

        if ($type === 'invoice') {
            if ($customer->status === 'debit') {
                $customer->amount -= $trxAmount;
                if ($customer->amount < 0) {
                    $customer->status = 'credit';
                    $customer->amount = abs($customer->amount);
                }
            } else {
                $customer->amount += $trxAmount;
            }
        } elseif ($type === 'payment') {
            if ($customer->status === 'debit') {
                $customer->amount += $trxAmount;
            } else {
                $customer->amount -= $trxAmount;
                if ($customer->amount < 0) {
                    $customer->status = 'debit';
                    $customer->amount = abs($customer->amount);
                }
            }
        }

        if ($customer->amount < 0) {
            $customer->amount = abs($customer->amount);
        }

        $customer->save();
    }

    public function transactionDestroy($id)
    {
        $trx = CustomerTrx::find($id);

        if (! $trx) {
            return back()->with('error', 'লেনদেন খুঁজে পাওয়া যায়নি।');
        }

        $latestTrx = CustomerTrx::where('customer_id', $trx->customer_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($latestTrx->id != $trx->id) {
            return back()->with('error', 'শুধুমাত্র সর্বশেষ লেনদেন ডিলিট করা যাবে।');
        }

        $customer = Customer::find($trx->customer_id);

        if (! $customer) {
            return back()->with('error', 'গ্রাহক খুঁজে পাওয়া যায়নি।');
        }

        $this->reverseTransaction($customer, $trx);

        $memoNo = $trx->invoice_type;

        if (! empty($memoNo)) {
            $memo = Memo::where('memo_no', $memoNo)->first();
            $memo->delete();
        }
        $trx->delete();

        return back()->with('success', 'লেনদেন সফলভাবে মুছে ফেলা হয়েছে এবং গ্রাহকের ব্যালেন্স আপডেট হয়েছে।');
    }

    public function transaction(Request $request)
    {
        $query = CustomerTrx::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('invoice_type', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay();
                $query->whereDate('created_at', $date);
            } catch (Exception $e) {
                return back()->with('error', 'তারিখ সঠিক ফরম্যাটে দিন (YYYY-MM-DD)।');
            }
        }

        $transactions = $query->orderBy('id', 'desc')->paginate(100);

        return view('customer.transaction', compact('transactions'));
    }

    public function customerTransaction(Request $request, $name, $id)
    {
        $customer = Customer::findOrFail($id);

        $query = CustomerTrx::where('customer_id', $customer->id);

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_type', 'like', "%{$search}%")
                    ->orWhere('payment', 'like', "%{$search}%")
                    ->orWhere('invoice', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay();
                $query->whereDate('created_at', $date);
            } catch (Exception $e) {
                return back()->with('error', 'তারিখ সঠিক ফরম্যাটে দিন (YYYY-MM-DD)।');
            }
        }

        $transactions = $query->orderBy('id', 'desc')->paginate(100);

        return view('customer.customerTransaction', compact('customer', 'transactions'));
    }

    private function storeTransaction(Request $request, string $type)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'invoice_type' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'numeric', 'min:0'],
                'created_at' => ['required', 'date_format:d/m/Y'],
            ], [
                'customer_id.required' => 'Customer select করা বাধ্যতামূলক।',
                'customer_id.exists' => 'বৈধ Customer নির্বাচন করুন।',
                'invoice_type.required' => 'Invoice type বাধ্যতামূলক।',
                'amount.required' => 'Amount বাধ্যতামূলক।',
                'amount.numeric' => 'Amount অবশ্যই সংখ্যায় দিন।',
                'created_at.required' => 'তারিখ নির্বাচন করুন।',
                'created_at.date_format' => 'তারিখ ফরম্যাট dd/mm/yyyy হতে হবে।',
            ]);

            $customer = Customer::findOrFail($request->customer_id);

            if (! in_array($customer->status, ['debit', 'credit'])) {
                $customer->status = 'debit';
            }

            $customerAmount = (float) $customer->amount;
            $requestAmount = (float) $request->amount;

            if ($type === 'invoice') {
                if ($customer->status === 'debit') {
                    $customer->amount = $customerAmount + $requestAmount;
                } else {
                    $customer->amount = $customerAmount - $requestAmount;
                    if ($customer->amount < 0) {
                        $customer->status = 'debit';
                        $customer->amount = abs($customer->amount);
                    }
                }
            } elseif ($type === 'payment') {
                if ($customer->status === 'debit') {
                    $customer->amount = $customerAmount - $requestAmount;
                    if ($customer->amount < 0) {
                        $customer->status = 'credit';
                        $customer->amount = abs($customer->amount);
                    }
                } else {
                    $customer->amount = $customerAmount + $requestAmount;
                }
            }

            if ($customer->amount < 0) {
                $customer->amount = abs($customer->amount);
            }

            if ($customer->amount == 0) {
                $customer->status = 'debit';
            }

            $customer->save();

            $trxData = [
                'customer_id' => $request->customer_id,
                'invoice_type' => $request->invoice_type,
                'debit_credit' => $customer->amount,
                'status' => $customer->status,
            ];

            if ($type === 'invoice') {
                $trxData['invoice'] = $request->amount;
                $trxData['invoice_status'] = 'Invoice';
            } else {
                $trxData['payment'] = $request->amount;
                $trxData['invoice_status'] = 'Payment';
            }

            $trx = CustomerTrx::create($trxData);
            $trx->created_at = Carbon::createFromFormat('d/m/Y', $request->created_at)->startOfDay();
            $trx->save();

            DB::commit();

            return redirect()->route('customer.transaction')
                ->with('success', 'লেনদেন সফলভাবে যুক্ত হয়েছে এবং ব্যালেন্স আপডেট হয়েছে।');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Transaction store failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'কিছু একটা সমস্যা হয়েছে। আবার চেষ্টা করুন।')->withInput();
        }
    }

    public function invoiceStore(Request $request)
    {
        return $this->storeTransaction($request, 'invoice');
    }

    public function paymentStore(Request $request)
    {
        return $this->storeTransaction($request, 'payment');
    }
}
