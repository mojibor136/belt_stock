<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query()->withCount(['groups', 'sizes']);

        if ($request->filled('search')) {
            $query->where('brand', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $brands = $query->orderBy('created_at', 'desc')->get();

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
        ]);

        try {
            Brand::create([
                'brand' => $request->name,
            ]);

            return redirect()->route('brands.index')
                             ->with('success', 'Brand created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Something went wrong: ' . $e->getMessage())
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
            'id'   => 'required|exists:brands,id',
            'name' => 'required|string',
        ]);

        try {
            $brand = Brand::findOrFail($request->id);
            $brand->brand = $request->name;
            $brand->save();

            return redirect()->route('brands.index')
                             ->with('success', 'Brand updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Something went wrong: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();

            return redirect()->route('brands.index')
                             ->with('success', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return back();
    }
}
