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

class SalesController extends Controller
{

public function index(Request $request)
{
    $query = Memo::with(['customer','items.brand','items.group','items.sizes']);

    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $query->where(function($q) use ($search) {
            $q->whereRaw('LOWER(memo_no) LIKE ?', ["%{$search}%"])
              ->orWhereHas('customer', function($q2) use ($search) {
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
                'error' => $e->getMessage()
            ]);
        }
    }

    $memos = $query->latest()->get();

    return view('sales.index', compact('memos'));
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
