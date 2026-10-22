<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // 1. Core Summary Metrics (calculated via database aggregate queries, NOT hardcoded)
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Sales Aggregates
        $salesTodayTotal = Sale::where('status', 'completed')
            ->whereDate('sale_date', $today)
            ->sum('total_amount');

        $salesTodayCount = Sale::where('status', 'completed')
            ->whereDate('sale_date', $today)
            ->count();

        $salesMonthTotal = Sale::where('status', 'completed')
            ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $salesMonthCount = Sale::where('status', 'completed')
            ->whereBetween('sale_date', [$startOfMonth, $endOfMonth])
            ->count();

        $purchasesMonthTotal = Purchase::where('status', 'completed')
            ->whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $purchasesMonthCount = Purchase::where('status', 'completed')
            ->whereBetween('purchase_date', [$startOfMonth, $endOfMonth])
            ->count();

        // Inventory Valuation
        $inventoryAssetValue = Product::select(DB::raw('SUM(stock * purchase_price) as total_asset_value'))
            ->value('total_asset_value') ?? 0;

        // 2. Low-Stock Products list & count
        $lowStockQuery = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc');

        $lowStockCount = (clone $lowStockQuery)->count();
        $lowStockProducts = (clone $lowStockQuery)->limit(6)->get();

        // 3. Recent Transactions
        $recentSales = Sale::with('user')
            ->latest('id')
            ->limit(5)
            ->get();

        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'salesTodayTotal',
            'salesTodayCount',
            'salesMonthTotal',
            'salesMonthCount',
            'purchasesMonthTotal',
            'purchasesMonthCount',
            'inventoryAssetValue',
            'lowStockCount',
            'lowStockProducts',
            'recentSales',
            'recentPurchases'
        ));
    }
}
