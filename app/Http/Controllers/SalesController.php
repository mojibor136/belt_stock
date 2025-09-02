<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Size;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function create()
    {
        return view('sales.create');
    }

    public function memo()
    {
        $brands = Brand::all();
        $customers = Customer::all();
        return view('memo.index', compact('brands', 'customers'));
    }

    public function getGroupData($groupId)
    {
        $group = Group::with('sizes')->find($groupId);

        $sizes = $group && $group->sizes
            ? $group->sizes->map(function ($size) {
                return [
                    'id'   => $size->id,
                    'size' => $size->size,
                ];
            })
            : collect([]);

        return response()->json([
            'rate'  => $group ? $group->sales_rate : 0,
            'sizes' => $sizes,
        ]);
    }

    public function memoStore(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'customer' => 'required|exists:customers,id',
                    'items' => 'required|array|min:1',
                    'items.*.brand_id' => 'nullable|exists:brands,id',
                    'items.*.group_id' => 'nullable|exists:groups,id',
                    'items.*.rate' => 'nullable|numeric|min:0',
                    'items.*.piece_rate' => 'nullable|numeric|min:0',
                    'items.*.sizes' => 'required|array|min:1',
                    'items.*.sizes.*.size' => 'required|numeric|min:0',
                    'items.*.sizes.*.quantity' => 'required|numeric|min:1',
                ],
                [
                    'customer.required' => 'কাস্টমার নির্বাচন করতে হবে।',
                    'customer.exists' => 'কাস্টমার পাওয়া যায়নি।',
                    'items.required' => 'কমপক্ষে একটি আইটেম যোগ করুন।',
                    'items.array' => 'আইটেমের ফরম্যাট সঠিক নয়।',
                    'items.min' => 'কমপক্ষে একটি আইটেম প্রয়োজন।',
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

            $data = $request->all();
            $grandTotal = 0;

            $customer = Customer::find($data['customer']);
            $data['customer_name'] = $customer->name;
            $data['customer_address'] = $customer->address;
            $data['customer_status'] = $customer->status;

            foreach ($data['items'] as $itemIndex => &$item) {
                if (isset($item['brand_id'])) {
                    $brand = Brand::find($item['brand_id']);
                    $item['brand_name'] = $brand ? $brand->brand : null;
                }

                if (isset($item['group_id'])) {
                    $group = Group::find($item['group_id']);
                    $item['group_name'] = $group ? $group->group : null;
                }

                $itemTotal = 0;
                $rate = isset($item['rate']) ? (float) $item['rate'] : 0;
                $pieceRate = isset($item['piece_rate']) ? (float) $item['piece_rate'] : 0;

                foreach ($item['sizes'] as $sizeIndex => &$size) {
                    $sizeVal = isset($size['size']) ? (float) $size['size'] : 0;
                    $sizeQty = isset($size['quantity']) ? (float) $size['quantity'] : 0;

                    if ($rate > 0) {
                        $sizeSubtotal = $sizeVal * $rate * $sizeQty;
                    } else {
                        $sizeSubtotal = $sizeQty * $pieceRate;
                    }

                    $size['subtotal'] = $sizeSubtotal;
                    $itemTotal += $sizeSubtotal;
                }

                $item['item_total'] = $itemTotal;
                $grandTotal += $itemTotal;
            }

            $data['grand_total'] = $grandTotal;

            return view('memo.show', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'সার্ভার এরর হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }
}
