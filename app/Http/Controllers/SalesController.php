<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Size;

class SalesController extends Controller
{
    public function index(){
        return view('sales.index');
    }

    public function create(){
        return view('sales.create');
    }

    public function memo(){
        $brands = Brand::all();
        $customers = Customer::all();
        return view('memo.index' , compact('brands' , 'customers'));
    }

public function getGroupData($groupId)
{
    $group = Group::with('sizes')->find($groupId);

    $sizes = $group->sizes 
        ? $group->sizes->map(function ($size) {
            return [
                'id'   => $size->id,
                'size' => $size->size
            ];
        }) 
        : collect([]);

    return response()->json([
        'rate'  => $group->sales_rate,
        'sizes' => $sizes
    ]);
}

public function memoStore(Request $request)
{
    try {
        $data = $request->all();
        $grandTotal = 0;

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemIndex => &$item) {
                $itemTotal = 0;
                $rate = isset($item['rate']) ? (float)$item['rate'] : 0;
                $pieceRate = isset($item['piece_rate']) ? (float)$item['piece_rate'] : 0;

                if (isset($item['sizes']) && is_array($item['sizes'])) {
                    foreach ($item['sizes'] as $sizeIndex => &$size) {
                        $sizeVal = isset($size['size']) ? (float)$size['size'] : 0;
                        $sizeQty = isset($size['quantity']) ? (float)$size['quantity'] : 0;

                        // Inch rate 0 hole piece rate diye hisab
                        if ($rate > 0) {
                            $sizeSubtotal = $sizeVal * $rate * $sizeQty;
                        } else {
                            $sizeSubtotal = $sizeQty * $pieceRate;
                        }

                        $size['subtotal'] = $sizeSubtotal;
                        $itemTotal += $sizeSubtotal;
                    }
                }

                $item['item_total'] = $itemTotal;
                $grandTotal += $itemTotal;
            }
        }

        $data['grand_total'] = $grandTotal;

        return view('memo.show', compact('data'));
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}



}
