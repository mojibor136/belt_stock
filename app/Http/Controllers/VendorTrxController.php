<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorTrx;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorTrxController extends Controller
{
    public function payment()
    {
        $vendors = Vendor::all();

        return view('vendor.payment', compact('vendors'));
    }

    public function invoice()
    {
        $vendors = Vendor::all();

        return view('vendor.invoice', compact('vendors'));
    }

    private function reverseTransaction(Vendor $vendor, VendorTrx $trx)
    {
        $vendorAmount = (float) $vendor->amount;
        $trxAmount = (float) $trx->amount;

        if ($trx->status === 'invoice') {
            if ($vendor->status === 'debit') {
                $vendor->amount -= $trxAmount;
                if ($vendor->amount < 0) {
                    $vendor->status = 'credit';
                    $vendor->amount = abs($vendor->amount);
                }
            } else {
                $vendor->amount += $trxAmount;
            }
        } else {
            if ($vendor->status === 'debit') {
                $vendor->amount += $trxAmount;
            } else {
                $vendor->amount -= $trxAmount;
                if ($vendor->amount < 0) {
                    $vendor->status = 'debit';
                    $vendor->amount = abs($vendor->amount);
                }
            }
        }

        if ($vendor->amount < 0) {
            $vendor->amount = abs($vendor->amount);
        }

        $vendor->save();
    }

    public function transactionDestroy($id)
    {
        $trx = VendorTrx::find($id);

        if (! $trx) {
            return back()->with('error', 'লেনদেন খুঁজে পাওয়া যায়নি।');
        }

        $latestTrx = VendorTrx::where('vendor_id', $trx->vendor_id)
            ->orderBy('id', 'desc')
            ->first();

        if ($latestTrx->id != $trx->id) {
            return back()->with('error', 'শুধুমাত্র সর্বশেষ লেনদেন ডিলিট করা যাবে।');
        }

        $vendor = Vendor::find($trx->vendor_id);

        if (! $vendor) {
            return back()->with('error', 'গ্রাহক খুঁজে পাওয়া যায়নি।');
        }

        $this->reverseTransaction($vendor, $trx);
        $trx->delete();

        return back()->with('success', 'লেনদেন সফলভাবে মুছে ফেলা হয়েছে এবং গ্রাহকের ব্যালেন্স আপডেট হয়েছে।');
    }

    private function storeTransaction(Request $request, string $type)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'vendor_id' => ['required', 'exists:vendors,id'],
                'invoice_type' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'numeric', 'min:0'],
                'created_at' => ['required', 'date_format:d/m/Y'],
            ], [
                'vendor_id.required' => 'Vendor select করা বাধ্যতামূলক।',
                'vendor_id.exists' => 'বৈধ Vendor নির্বাচন করুন।',
                'invoice_type.required' => 'Invoice type বাধ্যতামূলক।',
                'amount.required' => 'Amount বাধ্যতামূলক।',
                'amount.numeric' => 'Amount অবশ্যই সংখ্যায় দিন।',
                'created_at.required' => 'তারিখ নির্বাচন করুন।',
                'created_at.date_format' => 'তারিখ ফরম্যাট dd/mm/yyyy হতে হবে।',
            ]);

            $vendor = Vendor::findOrFail($request->vendor_id);

            if (! in_array($vendor->status, ['credit', 'debit'])) {
                $vendor->status = 'credit';
            }

            $vendorAmount = (float) $vendor->amount;
            $requestAmount = (float) $request->amount;

            if ($type === 'invoice') {
                if ($vendor->status === 'debit') {
                    $vendor->amount -= $requestAmount;
                    if ($vendor->amount < 0) {
                        $vendor->status = 'credit';
                        $vendor->amount = abs($vendor->amount);
                    }
                } else {
                    $vendor->amount += $requestAmount;
                    if ($vendor->amount < 0) {
                        $vendor->status = 'debit';
                        $vendor->amount = abs($vendor->amount);
                    }
                }
            } elseif ($type === 'payment') {
                if ($vendor->status === 'debit') {
                    $vendor->amount += $requestAmount;
                    if ($vendor->amount < 0) {
                        $vendor->status = 'credit';
                        $vendor->amount = abs($vendor->amount);
                    }
                } else {
                    $vendor->amount -= $requestAmount;
                    if ($vendor->amount < 0) {
                        $vendor->status = 'debit';
                        $vendor->amount = abs($vendor->amount);
                    }
                }
            }

            if ($vendor->amount == 0) {
                $vendor->status = 'credit';
            }

            $vendor->save();

            $trxData = [
                'vendor_id' => $request->vendor_id,
                'invoice_type' => $request->invoice_type,
                'debit_credit' => $vendor->amount,
                'status' => $vendor->status,
            ];

            if ($type === 'invoice') {
                $trxData['invoice'] = $request->amount;
                $trxData['invoice_status'] = 'Invoice';
            } else {
                $trxData['payment'] = $request->amount;
                $trxData['invoice_status'] = 'Payment';
            }

            $trx = VendorTrx::create($trxData);
            $trx->created_at = Carbon::createFromFormat('d/m/Y', $request->created_at)->startOfDay();
            $trx->save();

            DB::commit();

            return redirect()->route('vendor.transaction')
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

    public function transaction(Request $request)
    {
        $query = VendorTrx::with('vendor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vendor', function ($q) use ($search) {
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

        return view('vendor.transaction', compact('transactions'));
    }

    public function vendorTransaction(Request $request, $name, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $query = VendorTrx::where('vendor_id', $vendor->id);

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

        return view('vendor.vendorTransaction', compact('vendor', 'transactions'));
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
