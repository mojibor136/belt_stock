<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Group;
use App\Models\Size;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::with(['brand', 'group']);

        if ($request->filled('search')) {
            $query->where('size', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('group')) {
            $query->where('group_id', $request->group);
        }

        $sizesCollection = $query->get()->map(function ($size) {
            $size->sales = StockHistory::where('size', $size->size)
                ->where('brand', $size->brand->brand)
                ->where('group', $size->group->group)
                ->where('type', 'sales')
                ->sum('quantity');

            return $size;
        })->sortByDesc('sales'); 

        $perPage = 100;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $sizesCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $sizes = new LengthAwarePaginator(
            $currentItems,
            $sizesCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

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
            'brand' => 'required|exists:brands,id',
            'group' => 'required|exists:groups,id',
            'size' => 'required|string|max:255',
            'cost_rate' => 'required|numeric',
            'sales_rate' => 'required|numeric',
            'rate_type' => 'required|in:inch,pieces',
        ], [
            'brand.required' => 'ব্র্যান্ড নির্বাচন করা বাধ্যতামূলক।',
            'brand.exists' => 'বৈধ ব্র্যান্ড নির্বাচন করুন।',
            'group.required' => 'গ্রুপ নির্বাচন করা বাধ্যতামূলক।',
            'group.exists' => 'বৈধ গ্রুপ নির্বাচন করুন।',
            'size.required' => 'সাইজের নাম দিতে হবে।',
            'size.string' => 'সাইজের নাম অবশ্যই টেক্সট হতে হবে।',
            'size.max' => 'সাইজের নাম খুব বড়।',
            'cost_rate.required' => 'কস্ট রেট দিতে হবে।',
            'cost_rate.numeric' => 'কস্ট রেট অবশ্যই সংখ্যা হতে হবে।',
            'sales_rate.required' => 'সেলস রেট দিতে হবে।',
            'sales_rate.numeric' => 'সেলস রেট অবশ্যই সংখ্যা হতে হবে।',
            'rate_type.required' => 'রেট টাইপ নির্বাচন করতে হবে।',
            'rate_type.in' => 'রেট টাইপ অবশ্যই "inch" বা "pieces" হতে হবে।',
        ]);

        try {
            $exists = Size::where('brand_id', $request->brand)
                ->where('group_id', $request->group)
                ->where('size', $request->size)
                ->first();

            if ($exists) {
                return back()->withInput()
                    ->with('error', 'এই ব্র্যান্ড, গ্রুপ, এবং সাইজের কম্বিনেশন ইতিমধ্যেই আছে!');
            }

            Size::create([
                'brand_id' => $request->brand,
                'group_id' => $request->group,
                'size' => $request->size,
                'cost_rate' => $request->cost_rate,
                'sales_rate' => $request->sales_rate,
                'rate_type' => $request->rate_type,
            ]);

            return redirect()->route('sizes.index')
                ->with('success', 'সাইজ সফলভাবে তৈরি হয়েছে!');
        } catch (\Exception $e) {
            \Log::error('Size Store Error: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'কোনো সমস্যা হয়েছে! দয়া করে আবার চেষ্টা করুন।');
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
            'id' => 'required|exists:sizes,id',
            'brand' => 'required|exists:brands,id',
            'group' => 'required|exists:groups,id',
            'size' => 'required|string|max:255',
            'cost_rate' => 'required|numeric',
            'sales_rate' => 'required|numeric',
            'rate_type' => 'required|in:inch,pieces',
        ], [
            'id.required' => 'সাইজ আইডি প্রয়োজন।',
            'id.exists' => 'সাইজ পাওয়া যায়নি।',
            'brand.required' => 'ব্র্যান্ড নির্বাচন করা বাধ্যতামূলক।',
            'brand.exists' => 'বৈধ ব্র্যান্ড নির্বাচন করুন।',
            'group.required' => 'গ্রুপ নির্বাচন করা বাধ্যতামূলক।',
            'group.exists' => 'বৈধ গ্রুপ নির্বাচন করুন।',
            'size.required' => 'সাইজের নাম দিতে হবে।',
            'size.string' => 'সাইজের নাম অবশ্যই টেক্সট হতে হবে।',
            'size.max' => 'সাইজের নাম খুব বড়।',
            'cost_rate.required' => 'কস্ট রেট দিতে হবে।',
            'cost_rate.numeric' => 'কস্ট রেট অবশ্যই সংখ্যা হতে হবে।',
            'sales_rate.required' => 'সেলস রেট দিতে হবে।',
            'sales_rate.numeric' => 'সেলস রেট অবশ্যই সংখ্যা হতে হবে।',
            'rate_type.required' => 'রেট টাইপ নির্বাচন করতে হবে।',
            'rate_type.in' => 'রেট টাইপ অবশ্যই "inch" বা "pieces" হতে হবে।',
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
                    ->with('error', 'এই ব্র্যান্ড, গ্রুপ, এবং সাইজের কম্বিনেশন ইতিমধ্যেই আছে!');
            }

            $size->update([
                'brand_id' => $request->brand,
                'group_id' => $request->group,
                'size' => $request->size,
                'cost_rate' => $request->cost_rate,
                'sales_rate' => $request->sales_rate,
                'rate_type' => $request->rate_type,
            ]);

            return redirect()->route('sizes.index')
                ->with('success', 'সাইজ সফলভাবে আপডেট হয়েছে!');
        } catch (\Exception $e) {
            \Log::error('Size Update Error: '.$e->getMessage());

            return back()->withInput()
                ->with('error', 'কোনো সমস্যা হয়েছে! দয়া করে আবার চেষ্টা করুন।');
        }
    }

    public function destroy($id)
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();

            return redirect()->route('sizes.index')
                ->with('success', 'সাইজ সফলভাবে ডিলিট হয়েছে।');
        } catch (\Exception $e) {
            return redirect()->route('sizes.index')
                ->with('error', $e->getMessage());
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
            'cost_rate' => $group->cost_rate ?? '',
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
