<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Group;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::all();

        $query = Group::with('brand')
            ->withCount('sizes')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('group', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        $groups = $query->get();

        return view('group.index', compact('groups', 'brands'));
    }

    public function getGroupRate($group_id)
    {
        $group = Group::find($group_id);

        if ($group) {
            return response()->json([
                'cost_rate'  => $group->cost_rate,
                'sales_rate' => $group->sales_rate,
            ]);
        }

        return response()->json([
            'cost_rate'  => '',
            'sales_rate' => '',
        ]);
    }

    public function create()
    {
        $brands = Brand::all();
        return view('group.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand'      => 'required|exists:brands,id',
            'group'      => 'required|string|max:255|unique:groups,group,NULL,id,brand_id,' . $request->brand,
            'cost_rate'  => 'required|numeric',
            'sales_rate' => 'required|numeric',
        ]);

        try {
            Group::create([
                'brand_id'   => $request->brand,
                'group'      => $request->group,
                'cost_rate'  => $request->cost_rate,
                'sales_rate' => $request->sales_rate,
                'rate_type'  => 'inch',
            ]);

            return redirect()->route('groups.index')
                             ->with('success', 'Group created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $group  = Group::with('brand')->findOrFail($id);
        $brands = Brand::all();

        return view('group.edit', compact('group', 'brands'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'         => 'required|exists:groups,id',
            'brand_id'   => 'required|exists:brands,id',
            'group'      => [
                'required',
                'string',
                'max:255',
                Rule::unique('groups')
                    ->where(fn($query) => $query->where('brand_id', $request->brand_id))
                    ->ignore($request->id),
            ],
            'cost_rate'  => 'required|numeric',
            'sales_rate' => 'required|numeric',
        ]);

        try {
            $group              = Group::findOrFail($request->id);
            $group->brand_id    = $request->brand_id;
            $group->group       = $request->group;
            $group->cost_rate   = $request->cost_rate;
            $group->sales_rate  = $request->sales_rate;
            $group->save();

            return redirect()->route('groups.index')
                             ->with('success', 'Group updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('groups.index')
                             ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $group = Group::findOrFail($request->id);
            $group->delete();

            return redirect()->route('groups.index')
                             ->with('success', 'Group deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('groups.index')
                             ->with('error', 'Failed to delete group: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return back();
    }
}
