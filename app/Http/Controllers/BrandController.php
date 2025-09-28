<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query()->withCount(['groups', 'sizes']);

        if ($request->filled('search')) {
            $query->where('brand', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $brands = $query->orderByDesc('groups_count')
            ->orderByDesc('sizes_count')
            ->orderByDesc('created_at')
            ->paginate(100);

        return view('brand.index', compact('brands'));
    }

    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,brand',
        ], [
            'name.required' => 'ব্র্যান্ডের নাম দিতে হবে।',
            'name.string' => 'ব্র্যান্ডের নাম অবশ্যই টেক্সট হতে হবে।',
            'name.unique' => 'এই ব্র্যান্ডের নাম ইতিমধ্যেই আছে!',
        ]);

        try {
            Brand::create([
                'brand' => $request->name,
            ]);

            return redirect()->route('brands.index')
                ->with('success', 'ব্র্যান্ড সফলভাবে তৈরি হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'কোনো সমস্যা হয়েছে: '.$e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:brands,id',
            'name' => [
                'required',
                'string',
                Rule::unique('brands', 'brand')->ignore($request->id),
            ],
        ], [
            'id.required' => 'ব্র্যান্ড আইডি প্রয়োজন।',
            'id.exists' => 'ব্র্যান্ড পাওয়া যায়নি।',
            'name.required' => 'ব্র্যান্ডের নাম দিতে হবে।',
            'name.string' => 'ব্র্যান্ডের নাম অবশ্যই টেক্সট হতে হবে।',
            'name.unique' => 'এই ব্র্যান্ডের নাম ইতিমধ্যেই আছে!',
        ]);

        try {
            $brand = Brand::findOrFail($request->id);
            $brand->brand = $request->name;
            $brand->save();

            return redirect()->route('brands.index')
                ->with('success', 'ব্র্যান্ড সফলভাবে আপডেট হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'কোনো সমস্যা হয়েছে: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            return redirect()->route('brands.index')
                ->with('success', 'ব্র্যান্ড সফলভাবে ডিলিট হয়েছে!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'কোনো সমস্যা হয়েছে: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        return back();
    }
}
