<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Size;
use App\Models\Stock;
use App\Models\MemoItem;
use App\Models\MemoItemSize;
use App\Models\Memo;
use App\Models\Customer;
use App\Models\CustomerTrx;
use Carbon\Carbon;
use Exception;

class MemoController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        $customers = Customer::all();
        return view('memo.index', compact('brands', 'customers'));
    }

    public function show($id)
    {
        $memo = Memo::with(['customer', 'items.sizes'])->findOrFail($id);

        $data = [
            'customer_name' => $memo->customer->name,
            'customer_address' => $memo->customer->address,
            'debit_credit_status' => $memo->debit_credit_status,
            'debit_credit' => $memo->debit_credit ?? 0,
            'items' => $memo->items->map(function ($item) {
                return [
                    'brand_name' => $item->brand->brand,
                    'group_name' => $item->group->group,
                    'rate' => $item->inch_rate,
                    'piece_rate' => $item->piece_rate,
                    'item_total' => $item->item_total,
                    'sizes' => $item->sizes->map(function ($size) {
                        return [
                            'size' => $size->size,
                            'quantity' => $size->quantity,
                            'subtotal' => $size->subtotal,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];

        return view('memo.show', compact('data'));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'customer' => 'required|exists:customers,id',
                    'items' => 'required|array|min:1',
                    'items.*.brand_id' => 'nullable|exists:brands,id',
                    'items.*.group_id' => 'nullable|exists:groups,id',
                    'items.*.inch_rate' => 'nullable|numeric|min:0',
                    'items.*.piece_rate' => 'nullable|numeric|min:0',
                    'items.*.sizes' => 'required|array|min:1',
                    'items.*.sizes.*.size' => 'required|numeric|min:0',
                    'items.*.sizes.*.quantity' => 'required|numeric|min:1',
                ],
                [
                    'customer.required' => 'কাস্টমার নির্বাচন করতে হবে।',
                    'customer.exists' => 'কাস্টমার পাওয়া যায়নি।',
                    'items.required' => 'কমপক্ষে একটি আইটেম যোগ করুন।',
                    'items.*.sizes.required' => 'প্রতিটি আইটেমের সাইজ দিতে হবে।',
                    'items.*.sizes.*.size.required' => 'সাইজ দিতে হবে।',
                    'items.*.sizes.*.quantity.required' => 'সাইজের পরিমাণ দিতে হবে।',
                    'items.*.sizes.*.quantity.min' => 'পরিমাণ ন্যূনতম ১ হতে হবে।',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', $validator->errors()->first())
                    ->withInput();
            }

            \DB::beginTransaction();

            $data = $request->all();
            $grandTotal = 0;

            $memo = Memo::create([
                'memo_no' => 'M-' . time(),
                'customer_id' => $data['customer'],
                'debit_credit' => $data['debit'] ?? 0,
                'memo_status' => 'pending',
                'debit_credit_status' => $data['debit_credit_status'] ?? null,
                'grand_total' => 0,
            ]);

            if (!empty($data['created_at'])) {
                $memo->update([
                    'created_at' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['created_at']),
                    'updated_at' => now(),
                ]);
            }

            foreach ($data['items'] as $itemIndex => $item) {

                $itemTotal = 0;

                $inchRate = isset($item['inch_rate'])
                    ? (float) $item['inch_rate']
                    : (isset($item['rate']) ? (float) $item['rate'] : 0);
                $pieceRate = isset($item['piece_rate']) ? (float) $item['piece_rate'] : 0;

                $costInchRate = 0;
                $costPieceRate = 0;
  

                $memoItem = $memo->items()->create([
                    'brand_id' => $item['brand_id'] ?? null,
                    'group_id' => $item['group_id'] ?? null,
                    'inch_rate' => $inchRate,
                    'piece_rate' => $pieceRate,
                    'cost_inch_rate' => 0,
                    'cost_piece_rate' => 0,
                    'item_total' => 0,
                ]);

                foreach ($item['sizes'] as $sizeIndex => $size) {
                    $sizeVal = isset($size['size']) ? (float) $size['size'] : 0;
                    $sizeQty = isset($size['quantity']) ? (float) $size['quantity'] : 0;

                    $subtotal = $inchRate > 0
                        ? $sizeVal * $inchRate * $sizeQty
                        : $pieceRate * $sizeQty;


                    $sizeData = Size::where('size', $sizeVal)
                    ->where('group_id', $item['group_id'])
                    ->where('brand_id', $item['brand_id'])
                    ->first();    

                if ($sizeData) {
                    if ($sizeData->rate_type === 'inch') {
                        $costInchRate = $sizeData->cost_rate;
                    } elseif ($sizeData->rate_type === 'pieces') {
                        $costPieceRate = $sizeData->cost_rate;
                    }
                }

                    $memoItemSize = $memoItem->sizes()->create([
                        'size' => $sizeVal,
                        'quantity' => $sizeQty,
                        'subtotal' => $subtotal,
                    ]);

                    $itemTotal += $subtotal;
                }

            $memoItem->update([
                'item_total' => $itemTotal,
                'cost_inch_rate' => $costInchRate,
                'cost_piece_rate' => $costPieceRate,
            ]);

                $grandTotal += $itemTotal;
            }

            $memo->update(['grand_total' => $grandTotal]);

            \DB::commit();

            return redirect()->route('memo.show', $memo->id)
                ->with('success', 'মেমো সফলভাবে তৈরি হয়েছে।');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'সার্ভার এরর হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function pending(Request $request)
{
    $query = Memo::with('customer')
        ->where('memo_status', 'pending');

    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $query->whereRaw('LOWER(memo_no) LIKE ?', ["%{$search}%"]);
    }

    if ($request->filled('created_at')) {
        try {
            $date = Carbon::createFromFormat('d/m/Y', $request->created_at)
                ->format('Y-m-d');
            $query->whereDate('created_at', $date);
        } catch (\Exception $e) {
            \Log::error('Pending memo invalid date', [
                'input' => $request->created_at,
                'error' => $e->getMessage()
            ]);
        }
    }

    $memo = $query->latest()->get();

    return view('memo.pending', compact('memo'));
}

public function complete(Request $request)
{
    $query = Memo::with('customer')
        ->where('memo_status', 'complete');

    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $query->whereRaw('LOWER(memo_no) LIKE ?', ["%{$search}%"]);
    }

    if ($request->filled('created_at')) {
        try {
            $date = Carbon::createFromFormat('d/m/Y', $request->created_at)
                ->format('Y-m-d');
            $query->whereDate('created_at', $date);
        } catch (\Exception $e) {
            \Log::error('Complete memo invalid date', [
                'input' => $request->created_at,
                'error' => $e->getMessage()
            ]);
        }
    }

    $memo = $query->latest()->get();

    return view('memo.complete', compact('memo'));
}


public function status($id)
{
    // Memo data find
    $memo = Memo::findOrFail($id);

    // শুধুমাত্র Memo data ব্যবহার
    $amount       = $memo->grand_total;
    $invoice_type = $memo->memo_no;
    $customer_id  = $memo->customer_id;
    $created_at   = $memo->created_at->format('d/m/Y');

    if ($memo->memo_status == 'pending') {
        // Memo data দিয়ে transaction store করা
        return $this->storeTransactionFromMemo($memo, $customer_id, $invoice_type, $amount, $created_at, 'invoice');
    } else {
        return back();
    }
}

private function storeTransactionFromMemo($memo, $customer_id, $invoice_type, $amount, $created_at, string $type)
{
    DB::beginTransaction();

    try {
        // Customer find
        $customer = Customer::findOrFail($customer_id);

        if (!in_array($customer->status, ['debit', 'credit'])) {
            $customer->status = 'debit';
        }

        $customerAmount = (float) $customer->amount;
        $requestAmount  = (float) $amount;

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

        // Transaction data create
        $trxData = [
            'customer_id'    => $customer_id,
            'invoice_type'   => $invoice_type,
            'debit_credit'   => $customer->amount,
            'status'         => $customer->status,
        ];

        if ($type === 'invoice') {
            $trxData['invoice']        = $amount;
            $trxData['invoice_status'] = 'Invoice';
        } else {
            $trxData['payment']        = $amount;
            $trxData['invoice_status'] = 'Payment';
        }

        $trx = CustomerTrx::create($trxData);
        $trx->created_at = Carbon::createFromFormat('d/m/Y', $created_at)->startOfDay();
        $trx->save();

        $memo->memo_status = 'complete';
        $memo->save();

        DB::commit();

        return redirect()->route('customer.transaction')
            ->with('success', 'লেনদেন সফলভাবে যুক্ত হয়েছে এবং ব্যালেন্স আপডেট হয়েছে।');

    } catch (Exception $e) {
        DB::rollBack();

        Log::error('Transaction store failed', [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->with('error', 'কিছু একটা সমস্যা হয়েছে। আবার চেষ্টা করুন।');
    }
}


public function destroy($id)
{
    try {
        $memo = Memo::findOrFail($id);

        if ($memo->memo_status !== 'pending') {
            return redirect()->route('memo.pending')
                ->with('error', 'শুধুমাত্র Pending মেমো ডিলিট করা যাবে।');
        }

        $memo->delete();

        return redirect()->route('memo.pending')
            ->with('success', 'মেমো সফলভাবে ডিলিট হয়েছে!');
    } catch (\Exception $e) {
        \Log::error('Memo Delete Error: ' . $e->getMessage());

        return redirect()->route('memo.pending')
            ->with('error', 'মেমো ডিলিট করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
    }
}

public function checkQuantity($brandId, $groupId, $sizeValue)
{
    try {
        // Brand → Group
        $brand = Brand::find($brandId);
        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        $group = $brand->groups()->find($groupId);
        if (!$group) {
            return response()->json(['error' => 'Group not found in this brand'], 404);
        }

        // UI থেকে আসা size number দিয়ে Size model এ খোঁজা
        $size = $group->sizes()->where('size', $sizeValue)->first();
        if (!$size) {
            return response()->json(['error' => 'Size not found in this group'], 404);
        }

        // stock quantity sum using size_id
        $quantity = Stock::where('brand_id', $brandId)
                         ->where('group_id', $groupId)
                         ->where('size_id', $size->id)
                         ->sum('quantity');

        return response()->json([
            'brand' => $brand->brand,
            'group' => $group->group,
            'size' => $size->size,
            'available_quantity' => $quantity
        ]);

    } catch (\Exception $e) {
        // Log the error
        Log::error('Check Quantity Error: ' . $e->getMessage(), [
            'brand_id' => $brandId,
            'group_id' => $groupId,
            'size' => $sizeValue
        ]);

        return response()->json([
            'error' => 'Something went wrong while checking quantity.'
        ], 500);
    }
}


    
}
