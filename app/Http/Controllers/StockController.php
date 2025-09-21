<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Group;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['brand', 'group', 'size'])->orderBy('id', 'desc');

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }
        if ($request->filled('group')) {
            $query->where('group_id', $request->group);
        }
        if ($request->filled('size')) {
            $query->where('size_id', $request->size);
        }
        if ($request->filled('search')) {
            $query->where('quantity', 'like', '%'.$request->search.'%');
        }

        $stocks = $query->get();
        $brands = Brand::all();
        $groups = Group::all();
        $sizes = Size::all();

        return view('stock.index', compact('stocks', 'brands', 'groups', 'sizes'));
    }

    public function create()
    {
        $brands = Brand::all();

        return view('stock.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|exists:brands,id',
            'group' => 'required|exists:groups,id',
            'size' => 'required|exists:sizes,id',
            'quantity' => 'required|numeric|min:1',
            'alert' => 'required|numeric|min:1',
            'created_at' => 'nullable|date_format:d/m/Y',
        ]);

        try {
            $stock = Stock::where('brand_id', $request->brand)
                ->where('group_id', $request->group)
                ->where('size_id', $request->size)
                ->first();

            $brandName = Brand::find($request->brand)->brand;
            $groupName = Group::find($request->group)->group;
            $sizeName = Size::find($request->size)->size;

            $data = [
                'brand_id' => $request->brand,
                'group_id' => $request->group,
                'size_id' => $request->size,
                'alert' => $request->alert,
            ];

            if ($request->filled('created_at')) {
                $data['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
            }

            if ($stock) {
                $stock->increment('quantity', $request->quantity);
                $stock->update($data);
            } else {
                $data['quantity'] = $request->quantity;
                $stock = Stock::create($data);
            }

            StockHistory::create([
                'brand' => $brandName,
                'group' => $groupName,
                'size' => $sizeName,
                'quantity' => $request->quantity,
                'type' => 'add',
            ]);

            return redirect()->route('stocks.index')->with('success', 'Stock processed successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
        $stock = Stock::with(['brand', 'group', 'size'])->findOrFail($id);
        $brands = Brand::all();

        return view('stock.edit', compact('stock', 'brands'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand' => 'required|exists:brands,id',
            'group' => 'required|exists:groups,id',
            'size' => 'required|exists:sizes,id',
            'quantity' => 'required|numeric|min:0',
            'alert' => 'required|numeric|min:0',
            'created_at' => 'nullable|date_format:d/m/Y',
        ]);

        try {
            $stock = Stock::findOrFail($request->id);

            $data = [
                'brand_id' => $request->brand,
                'group_id' => $request->group,
                'size_id' => $request->size,
                'quantity' => $request->quantity,
                'alert' => $request->alert,
            ];

            if ($request->filled('created_at')) {
                $data['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
            }

            $stock->update($data);

            $brandName = Brand::find($request->brand)->brand;
            $groupName = Group::find($request->group)->group;
            $sizeName = Size::find($request->size)->size;

            StockHistory::create([
                'brand' => $brandName,
                'group' => $groupName,
                'size' => $sizeName,
                'quantity' => $request->quantity,
                'type' => 'edit',
            ]);

            return redirect()->route('stocks.index')->with('success', 'Stock updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function history(Request $request)
    {
        $query = StockHistory::orderBy('id', 'desc');

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }
        if ($request->filled('search')) {
            $query->where('quantity', 'like', '%'.$request->search.'%');
        }

        $histories = $query->get();
        $brands = Brand::all();
        $groups = Group::all();
        $sizes = Size::all();

        return view('stock.history', compact('histories', 'brands', 'groups', 'sizes'));
    }

    public function warnings()
    {
        $stocks = Stock::with(['brand', 'group', 'size'])
            ->whereColumn('quantity', '<=', 'alert')
            ->where('quantity', '>', 0)
            ->orderBy('id', 'desc')
            ->get();

        $brands = Brand::all();
        $groups = Group::all();
        $sizes = Size::all();

        return view('stock.warnings', compact('stocks', 'brands', 'groups', 'sizes'));
    }

    public function exhausted()
    {
        $stocks = Stock::with(['brand', 'group', 'size'])
            ->where('quantity', '=', 0)
            ->orderBy('id', 'desc')
            ->get();

        $brands = Brand::all();
        $groups = Group::all();
        $sizes = Size::all();

        return view('stock.exhausted', compact('stocks', 'brands', 'groups', 'sizes'));
    }

    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $brandName = $stock->brand->brand;
            $groupName = $stock->group->group;
            $sizeName = $stock->size->size;
            $quantity = $stock->quantity;

            StockHistory::create([
                'brand' => $brandName,
                'group' => $groupName,
                'size' => $sizeName,
                'quantity' => $quantity,
                'type' => 'delete',
            ]);

            $stock->delete();

            return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete stock. Please try again.');
        }
    }
}
