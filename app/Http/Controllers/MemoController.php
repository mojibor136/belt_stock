<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Size;
use App\Models\MemoItem;
use App\Models\MemoItemSize;
use App\Models\Memo;
use Carbon\Carbon;

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

                $memoItem = $memo->items()->create([
                    'brand_id' => $item['brand_id'] ?? null,
                    'group_id' => $item['group_id'] ?? null,
                    'inch_rate' => $inchRate,
                    'piece_rate' => $pieceRate,
                    'item_total' => 0,
                ]);

                foreach ($item['sizes'] as $sizeIndex => $size) {
                    $sizeVal = isset($size['size']) ? (float) $size['size'] : 0;
                    $sizeQty = isset($size['quantity']) ? (float) $size['quantity'] : 0;

                    $subtotal = $inchRate > 0
                        ? $sizeVal * $inchRate * $sizeQty
                        : $pieceRate * $sizeQty;

                    $memoItemSize = $memoItem->sizes()->create([
                        'size' => $sizeVal,
                        'quantity' => $sizeQty,
                        'subtotal' => $subtotal,
                    ]);

                    $itemTotal += $subtotal;
                }

                $memoItem->update(['item_total' => $itemTotal]);

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

    
}
