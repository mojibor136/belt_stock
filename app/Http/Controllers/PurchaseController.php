<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Group;
use App\Models\PurchaseItem;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StockHistory;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = PurchaseItem::with(['vendor', 'group', 'brand'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('updated_at')
            ->paginate(100);

        return view('purchase.index', compact('purchases'));
    }

    public function create()
    {
        $brands = Brand::all();
        $vendors = Vendor::all();

        return view('purchase.create', compact('brands', 'vendors'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendor' => 'required|exists:vendors,id',
                'brand' => 'required|exists:brands,id',
                'group' => 'required|exists:groups,id',
                'size' => 'required|exists:sizes,id',
                'quantity' => 'required|integer|min:1',
                'created_at' => 'required|date_format:d/m/Y',
            ]);

            $vendor = Vendor::findOrFail($validated['vendor']);
            $brand = Brand::findOrFail($validated['brand']);
            $group = Group::findOrFail($validated['group']);
            $size = Size::findOrFail($validated['size']);
            $createdAt = $validated['created_at'];

            $purchase = PurchaseItem::create([
                'vendor_id' => $vendor->id,
                'brand_id' => $brand->id,
                'group_id' => $group->id,
                'size' => $size->size,
                'quantity' => $validated['quantity'],
                'status' => 'pending',
            ]);

            $purchase->created_at = Carbon::createFromFormat('d/m/Y', $request->created_at)->startOfDay();

            $purchase->save();

            $stock = Stock::where('brand_id', $brand->id)
                ->where('group_id', $group->id)
                ->where('size_id', $size->id)
                ->first();

            if ($stock) {

                $stock->increment('quantity', $validated['quantity']);

            } else {
                $stock = Stock::create([
                    'brand_id' => $brand->id,
                    'group_id' => $group->id,
                    'size_id' => $size->id,
                    'quantity' => $validated['quantity'],
                    'alert' => 1,
                ]);

                $stock->created_at = Carbon::createFromFormat('d/m/Y', $request->created_at)->startOfDay();

                $stock->save();
            }

            $history = StockHistory::create([
                'brand' => $brand->brand,
                'group' => $group->group,
                'size' => $size->size,
                'quantity' => $validated['quantity'],
                'type' => 'purchase',
            ]);

            $history->created_at = Carbon::createFromFormat('d/m/Y', $request->created_at)->startOfDay();

            $history->save();

            return redirect()
                ->route('purchase.index')
                ->with('success', 'Purchase created & stock updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create purchase: '.$e->getMessage());
        }
    }

    public function pending()
    {
        $purchases = PurchaseItem::with(['vendor', 'group', 'brand'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(100);

        return view('purchase.pending', compact('purchases'));
    }

    public function confirm()
    {
        $purchases = PurchaseItem::with(['vendor', 'group', 'brand'])
            ->where('status', 'confirm')
            ->latest()
            ->paginate(100);

        return view('purchase.confirm', compact('purchases'));
    }

    public function status($id)
    {
        try {
            $purchase = PurchaseItem::findOrFail($id);

            if ($purchase->status === 'confirm') {
                return redirect()
                    ->back()
                    ->with('error', 'Purchase is already confirmed.');
            }

            $purchase->status = 'confirm';
            $purchase->save();

            return redirect()
                ->route('purchase.pending')
                ->with('success', 'Purchase status updated to confirm.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update purchase status: '.$e->getMessage());
        }
    }
}
