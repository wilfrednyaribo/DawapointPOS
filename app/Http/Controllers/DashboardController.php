<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\Sale;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's stats
        $todaySales = Sale::completed()->today()->sum('total');
        $todayTransactions = Sale::completed()->today()->count();
        
        // FIXED: Explicitly select 'sales.created_at' to avoid ambiguous column error
        $todayProfit = Sale::query()
            ->where('sales.status', 'completed')
            ->whereDate('sales.created_at', today())
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('drugs', 'sale_items.drug_id', '=', 'drugs.id')
            ->selectRaw('SUM((sale_items.unit_price - drugs.cost_price) * sale_items.quantity) as profit')
            ->value('profit') ?? 0;

        // Monthly stats
        $monthlySales = Sale::completed()->thisMonth()->sum('total');
        $monthlyTransactions = Sale::completed()->thisMonth()->count();

        // Inventory alerts
        $lowStockDrugs = Drug::whereColumn('quantity_in_stock', '<=', 'reorder_level')
            ->with('category')
            ->orderBy('quantity_in_stock')
            ->take(10)
            ->get();

        $expiringBatches = DrugBatch::where('quantity_remaining', '>', 0)
            ->where('expiry_date', '<=', now()->addDays(90))
            ->where('expiry_date', '>', now())
            ->with('drug')
            ->orderBy('expiry_date')
            ->take(10)
            ->get();

        $expiredBatches = DrugBatch::where('quantity_remaining', '>', 0)
            ->where('expiry_date', '<', now())
            ->with('drug')
            ->orderBy('expiry_date')
            ->get();

        // Recent sales
        $recentSales = Sale::with(['customer', 'user', 'items.drug'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Sales chart data (last 7 days)
        $salesChart = Sale::completed()
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling drugs
        $topDrugs = Drug::withSum(['saleItems as total_sold' => function($query) {
                $query->whereHas('sale', function($q) {
                    $q->where('status', 'completed')->whereMonth('created_at', now()->month);
                });
            }], 'quantity')
            ->having('total_sold', '>', 0)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Category distribution
        $categoryDistribution = Category::withCount(['drugs as drug_count'])
            ->withSum(['drugs as stock_value' => function($query) {
                $query->selectRaw('SUM(quantity_in_stock * cost_price)');
            }], 'quantity_in_stock')
            ->having('drug_count', '>', 0)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayTransactions',
            'todayProfit',
            'monthlySales',
            'monthlyTransactions',
            'lowStockDrugs',
            'expiringBatches',
            'expiredBatches',
            'recentSales',
            'salesChart',
            'topDrugs',
            'categoryDistribution'
        ));
    }
}