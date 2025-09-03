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

    public function getGroupData($groupId)
    {
        $group = Group::with('sizes')->find($groupId);

        $sizes = $group && $group->sizes
            ? $group->sizes->map(function ($size) {
                return [
                    'id' => $size->id,
                    'size' => $size->size,
                ];
            })
            : collect([]);

        return response()->json([
            'rate' => $group ? $group->sales_rate : 0,
            'sizes' => $sizes,
        ]);
    }
}
