<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Brand;
use App\Models\Size;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::with(['brand', 'group'])->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('size', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('group')) {
            $query->where('group_id', $request->group);
        }

        $sizes = $query->get();
        $brands = Brand::all();
        $groups = Group::all();

        return view('size.index', compact('sizes', 'brands', 'groups'));
    }

    public function create()
    {
        $brands = Brand::all();
        $groups = Group::all();
        return view('size.create', compact('brands', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand'      => 'required|exists:brands,id',
            'group'      => 'required|exists:groups,id',
            'size'       => 'required|string|max:255',
            'cost_rate'  => 'required|numeric',
            'sales_rate' => 'required|numeric',
            'rate_type'  => 'required|in:inch,pieces',
        ]);

        try {
            $exists = Size::where('brand_id', $request->brand)
                ->where('group_id', $request->group)
                ->where('size', $request->size)
                ->first();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'This combination of Brand, Group, and Size already exists!');
            }

            Size::create([
                'brand_id'   => $request->brand,
                'group_id'   => $request->group,
                'size'       => $request->size,
                'cost_rate'  => $request->cost_rate,
                'sales_rate' => $request->sales_rate,
                'rate_type'  => $request->rate_type,
            ]);

            return redirect()->route('sizes.index')
                ->with('success', 'Size created successfully!');
        } catch (\Exception $e) {
            \Log::error('Size Store Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
        $size = Size::with(['brand', 'group'])->findOrFail($id);
        $brands = Brand::all();
        $groups = Group::where('brand_id', $size->brand_id)->get();

        return view('size.edit', compact('size', 'brands', 'groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'         => 'required|exists:sizes,id',
            'brand'      => 'required|exists:brands,id',
            'group'      => 'required|exists:groups,id',
            'size'       => 'required|string|max:255',
            'cost_rate'  => 'required|numeric',
            'sales_rate' => 'required|numeric',
            'rate_type'  => 'required|in:inch,pieces',
        ]);

        try {
            $size = Size::findOrFail($request->id);

            $exists = Size::where('brand_id', $request->brand)
                ->where('group_id', $request->group)
                ->where('size', $request->size)
                ->where('id', '!=', $request->id)
                ->first();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'This combination of Brand, Group, and Size already exists!');
            }

            $size->update([
                'brand_id'   => $request->brand,
                'group_id'   => $request->group,
                'size'       => $request->size,
                'cost_rate'  => $request->cost_rate,
                'sales_rate' => $request->sales_rate,
                'rate_type'  => $request->rate_type,
            ]);

            return redirect()->route('sizes.index')
                ->with('success', 'Size updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Size Update Error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();

            return redirect()->route('sizes.index')
                ->with('success', 'Size deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('sizes.index')
                ->with('error', 'Something went wrong while deleting.');
        }
    }

    public function getGroups($brand_id)
    {
        $groups = Group::where('brand_id', $brand_id)->get();
        return response()->json($groups);
    }

    public function getGroupsByBrand(Request $request)
    {
        $groups = Group::where('brand_id', $request->brand_id)->get();
        return response()->json($groups);
    }

    public function getGroupRate(Request $request)
    {
        $group = Group::find($request->group_id);

        return response()->json([
            'cost_rate'  => $group->cost_rate ?? '',
            'sales_rate' => $group->sales_rate ?? '',
        ]);
    }

    public function getSizesByGroup(Request $request)
    {
        $sizes = Size::where('group_id', $request->group_id)->get();
        return response()->json($sizes);
    }

    public function show($id)
    {
        return back();
    }
}
