<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Size;
use App\Models\Stock;

class HomeController extends Controller
{
    public function index()
    {
        $data = $this->completeMemosSalesProfit();
        $totalCustomer = Customer::count();
        $customerDebit = Customer::where('status', 'debit')->sum('amount');
        $customerCredit = Customer::where('status', 'credit')->sum('amount');
        $totalVendor = Vendor::count();
        $vendorCredit = Vendor::where('status', 'credit')->sum('amount');
        $vendorDebit = Vendor::where('status', 'debit')->sum('amount');
        $totalBrand = Brand::count();
        $totalGroup = Group::count();
        $totalSize = Size::count();
        $totalStock = Stock::sum('quantity');
        $exhaustedStock = Stock::where('quantity', 0)->count();

        $stockValue = Size::with('stocks')->get()->sum(function ($size) {
            return $size->stocks->sum(function ($stock) use ($size) {
               return $size->size * $size->cost_rate * $stock->quantity;
            });
        });

        return view('dashboard', compact('data' , 'exhaustedStock' , 'stockValue' , 'totalStock' , 'totalSize' , 'totalGroup' , 'totalBrand' , 'vendorCredit' , 'vendorDebit' , 'totalVendor' , 'customerDebit' , 'customerCredit' , 'totalCustomer'));
    }

    private function completeMemosSalesProfit()
    {
        $memos = Memo::where('memo_status', 'complete')->with('items.sizes')->get();

        $dailyProfit = [];
        $monthlyProfit = [];
        $yearlyProfit = [];

        $dailySales = [];
        $monthlySales = [];
        $yearlySales = [];

        foreach ($memos as $memo) {
            $memoProfit = 0;
            $memoSales = 0;

            foreach ($memo->items as $item) {
                foreach ($item->sizes as $size) {
                    $sales = $item->inch_rate > 0
                        ? $size->size * $item->inch_rate * $size->quantity
                        : $item->piece_rate * $size->quantity;

                    $cost = $item->cost_inch_rate > 0
                        ? $size->size * $item->cost_inch_rate * $size->quantity
                        : $item->cost_piece_rate * $size->quantity;

                    $memoSales += $sales;
                    $memoProfit += ($sales - $cost);
                }
            }

            $date = $memo->created_at->format('Y-m-d');
            $month = $memo->created_at->format('Y-m');
            $year = $memo->created_at->format('Y');

            $dailySales[$date][] = $memoSales;
            $monthlySales[$month][] = $memoSales;
            $yearlySales[$year][] = $memoSales;

            $dailyProfit[$date][] = $memoProfit;
            $monthlyProfit[$month][] = $memoProfit;
            $yearlyProfit[$year][] = $memoProfit;
        }

        return [
            'daily' => [
                'sales' => array_map(fn($v) => array_sum($v), $dailySales),
                'profit' => array_map(fn($v) => array_sum($v), $dailyProfit),
            ],
            'monthly' => [
                'sales' => array_map(fn($v) => array_sum($v), $monthlySales),
                'profit' => array_map(fn($v) => array_sum($v), $monthlyProfit),
            ],
            'yearly' => [
                'sales' => array_map(fn($v) => array_sum($v), $yearlySales),
                'profit' => array_map(fn($v) => array_sum($v), $yearlyProfit),
            ],
        ];
    }
}
