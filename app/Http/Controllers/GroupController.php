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

        $query = Group::with(['brand', 'sizes.stocks'])
            ->withCount('sizes')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('group', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        $groups = $query->get()->map(function ($group) {
            $group->total_inchi = 0;
            $group->total_value = 0;

            foreach ($group->sizes as $size) {
                $quantity = $size->stocks->sum('quantity');

                if ($size->rate_type === 'inch') {
                    $group->total_inchi += $size->size * $quantity;
                }

                $group->total_value += $size->size * $size->cost_rate * $quantity;
            }

            return $group;
        });

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
        ], [
            'brand.required'      => 'ব্র্যান্ড নির্বাচন করা বাধ্যতামূলক।',
            'brand.exists'        => 'বৈধ ব্র্যান্ড নির্বাচন করুন।',
            'group.required'      => 'গ্রুপের নাম দিতে হবে।',
            'group.unique'        => 'এই ব্র্যান্ডের জন্য গ্রুপের নাম ইতিমধ্যেই আছে!',
            'cost_rate.required'  => 'কস্ট রেট দিতে হবে।',
            'cost_rate.numeric'   => 'কস্ট রেট অবশ্যই সংখ্যা হতে হবে।',
            'sales_rate.required' => 'সেলস রেট দিতে হবে।',
            'sales_rate.numeric'  => 'সেলস রেট অবশ্যই সংখ্যা হতে হবে।',
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
                             ->with('success', 'গ্রুপ সফলভাবে তৈরি হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'কোনো সমস্যা হয়েছে: ' . $e->getMessage())
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
                    ->where(fn ($query) => $query->where('brand_id', $request->brand_id))
                    ->ignore($request->id),
            ],
            'cost_rate'  => 'required|numeric',
            'sales_rate' => 'required|numeric',
        ], [
            'id.required'         => 'গ্রুপ আইডি প্রয়োজন।',
            'id.exists'           => 'গ্রুপ পাওয়া যায়নি।',
            'brand_id.required'   => 'ব্র্যান্ড নির্বাচন করা বাধ্যতামূলক।',
            'brand_id.exists'     => 'বৈধ ব্র্যান্ড নির্বাচন করুন।',
            'group.required'      => 'গ্রুপের নাম দিতে হবে।',
            'group.unique'        => 'এই ব্র্যান্ডের জন্য গ্রুপের নাম ইতিমধ্যেই আছে!',
            'cost_rate.required'  => 'কস্ট রেট দিতে হবে।',
            'cost_rate.numeric'   => 'কস্ট রেট অবশ্যই সংখ্যা হতে হবে।',
            'sales_rate.required' => 'সেলস রেট দিতে হবে।',
            'sales_rate.numeric'  => 'সেলস রেট অবশ্যই সংখ্যা হতে হবে।',
        ]);

        try {
            $group             = Group::findOrFail($request->id);
            $group->brand_id   = $request->brand_id;
            $group->group      = $request->group;
            $group->cost_rate  = $request->cost_rate;
            $group->sales_rate = $request->sales_rate;
            $group->save();

            return redirect()->route('groups.index')
                             ->with('success', 'গ্রুপ সফলভাবে আপডেট হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->route('groups.index')
                             ->with('error', 'কোনো সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $group = Group::findOrFail($request->id);
            $group->delete();

            return redirect()->route('groups.index')
                             ->with('success', 'গ্রুপ সফলভাবে ডিলিট হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->route('groups.index')
                             ->with('error', 'কোনো সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return back();
    }
}
