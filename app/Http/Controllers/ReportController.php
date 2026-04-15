<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Category;
use App\Models\DrugBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response; // Required for CSV streaming





class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
{
    $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
    $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

    // 1. Get All Sales for the period
    $sales = Sale::completed()
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->with(['customer', 'user'])
        ->orderBy('created_at', 'desc')
        ->get();

    // 2. Calculate Summary Stats
    $summary = [
        'total_sales' => $sales->sum('total'),
        'total_transactions' => $sales->count(),
        'total_discount' => $sales->sum('discount'),
        'total_tax' => $sales->sum('tax'),
        'avg_transaction' => $sales->avg('total'),
        'by_payment_method' => $sales->groupBy('payment_method')->map->sum('total'),
    ];

    // 3. Get Daily Sales Breakdown
    $dailySales = Sale::completed()
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as transactions')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // 4. Get Top Selling Drugs (NEW LOGIC ADDED HERE)
    $topDrugs = SaleItem::whereHas('sale', function($q) use ($startDate, $endDate) {
        $q->completed()
          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    })
    ->select('drug_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_revenue'))
    ->groupBy('drug_name')
    ->orderByDesc('total_revenue')
    ->limit(5)
    ->get();

    // 5. Return EVERYTHING to the view at once
    return view('reports.sales', compact('sales', 'summary', 'dailySales', 'startDate', 'endDate', 'topDrugs'));
}
    public function inventoryReport(Request $request)
    {
        $drugs = Drug::with(['category', 'batches'])
            ->withSum('batches as total_batch_quantity', 'quantity_remaining')
            ->orderBy('name');

        if ($request->category) {
            $drugs->where('category_id', $request->category);
        }

        if ($request->stock_status === 'low') {
            $drugs->whereColumn('quantity_in_stock', '<=', 'reorder_level');
        } elseif ($request->stock_status === 'out') {
            $drugs->where('quantity_in_stock', '<=', 0);
        }

        $drugs = $drugs->get();

        $summary = [
            'total_items' => $drugs->count(),
            'total_quantity' => $drugs->sum('quantity_in_stock'),
            'total_value' => $drugs->sum(fn($d) => $d->quantity_in_stock * $d->cost_price),
            'retail_value' => $drugs->sum(fn($d) => $d->quantity_in_stock * $d->selling_price),
            'low_stock_count' => $drugs->where('is_low_stock', true)->count(),
            'out_of_stock_count' => $drugs->where('quantity_in_stock', '<=', 0)->count(),
        ];

        $categories = Category::orderBy('name')->get();

        return view('reports.inventory', compact('drugs', 'summary', 'categories'));
    }

 public function expiryReport(Request $request)
{
    // FIX 1: Force the input to be an integer. 
    // We use (int) to convert the string "90" to integer 90.
    // We use max(1, ...) to ensure nobody types "0" or negative numbers.
    $days = max(1, (int) $request->input('days', 30));

    // 1. Get Expired Batches (Expiry date is in the past)
    $expired = DrugBatch::where('expiry_date', '<', now())
                        ->where('quantity_remaining', '>', 0)
                        ->with('drug')
                        ->get();

    // 2. Get Expiring Soon Batches (Within the selected days window)
    // Now $days is safely an integer, so addDays() will work.
    $expiringSoon = DrugBatch::whereBetween('expiry_date', [now(), now()->addDays($days)])
                             ->where('quantity_remaining', '>', 0)
                             ->with('drug')
                             ->get();

    // 3. Calculate Total Loss (Already Expired)
    $totalLossValue = $expired->sum(function ($batch) {
        $cost = $batch->purchase_price ?? $batch->drug->cost_price ?? 0;
        return $batch->quantity_remaining * $cost;
    });

    // 4. Calculate Forecasted Loss (Expired + Expiring Soon)
    $projectedLossValue = $totalLossValue + $expiringSoon->sum(function ($batch) {
        $cost = $batch->purchase_price ?? $batch->drug->cost_price ?? 0;
        return $batch->quantity_remaining * $cost;
    });

    return view('reports.expiry', compact(
        'expired', 
        'expiringSoon', 
        'days', 
        'totalLossValue',
        'projectedLossValue'
    ));
}
    public function topProductsReport(Request $request)
    {
        $period = $request->period ?? 'month';

        $query = SaleItem::query()
            ->select('drug_id', 'drug_name', 
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(DISTINCT sale_id) as times_sold')
            )
            ->whereHas('sale', function($q) use ($period) {
                $q->where('status', 'completed');
                if ($period === 'week') {
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($period === 'month') {
                    $q->whereMonth('created_at', now()->month);
                } elseif ($period === 'year') {
                    $q->whereYear('created_at', now()->year);
                }
            })
            ->groupBy('drug_id', 'drug_name')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        return view('reports.top-products', compact('query', 'period'));
    }

    public function profitReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $profit = SaleItem::query()
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('drugs', 'sale_items.drug_id', '=', 'drugs.id')
            ->where('sales.status', 'completed')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                DB::raw('DATE(sales.created_at) as date'),
                DB::raw('SUM(sale_items.total) as revenue'),
                DB::raw('SUM(drugs.cost_price * sale_items.quantity) as cost'),
                DB::raw('SUM((sale_items.unit_price - drugs.cost_price) * sale_items.quantity) as profit')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalProfit = $profit->sum('profit');
        $totalRevenue = $profit->sum('revenue');
        $totalCost = $profit->sum('cost');

        return view('reports.profit', compact('profit', 'totalProfit', 'totalRevenue', 'totalCost', 'startDate', 'endDate'));
    }


  public function export(Request $request)
{
    $type = $request->input('type', 'sales');
    $format = $request->input('format', 'csv');
    $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->input('end_date', now()->format('Y-m-d'));

    $data = [];
    $filename = $type . '-report-' . date('Y-m-d');

    // 1. SALES EXPORT
     if ($type === 'sales') {
        $sales = Sale::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        foreach ($sales as $sale) {
            // FIX: Read directly from the 'sales' table column 'customer_name'
            // If the column is empty or null, it defaults to 'Walk-in'
            $customerName = $sale->customer_name ?? 'Walk-in';

            $data[] = [
                'Date' => $sale->created_at->format('Y-m-d H:i'),
                // Checks for invoice_number, receipt_number, or falls back to ID
                'Receipt/Invoice' => $sale->invoice_number ?? $sale->receipt_number ?? $sale->id,
                // FIXED: Now captures the name directly from the sale record
                'Customer' => $customerName, 
                'Total' => $sale->total,
                'Payment' => $sale->payment_method,
                'Status' => $sale->status
            ];
        }
    }
    
    // 2. INVENTORY EXPORT
    elseif ($type === 'inventory') {
        $drugs = Drug::with('category')
            ->orderBy('name')
            ->get();

        foreach ($drugs as $drug) {
            // FIX: Use 'quantity_in_stock' as requested
            $currentStock = $drug->quantity_in_stock ?? 0;
            
            $data[] = [
                'Drug Name' => $drug->name,
                'Category' => $drug->category->name ?? 'N/A',
                // FIX: Now uses the correct column
                'Stock Qty' => $currentStock, 
                'Selling Price' => $drug->selling_price,
                'Cost Price' => $drug->cost_price,
                // FIX: Calculates value based on correct stock
                'Stock Value' => $currentStock * $drug->cost_price 
            ];
        }
    } 
    
    // 3. EXPIRY EXPORT
    elseif ($type === 'expiry') {
        $batches = DrugBatch::with('drug')
            ->where('quantity_remaining', '>', 0)
            ->orderBy('expiry_date')
            ->get();

        foreach ($batches as $batch) {
            $status = $batch->expiry_date->isPast() ? 'EXPIRED' : 
                     ($batch->expiry_date->diffInDays(now()) < 30 ? 'EXPIRING SOON' : 'VALID');
            
            $data[] = [
                'Drug Name' => $batch->drug->name ?? 'N/A',
                'Batch No' => $batch->batch_number,
                'Expiry Date' => $batch->expiry_date->format('Y-m-d'),
                'Qty Remaining' => $batch->quantity_remaining,
                'Status' => $status
            ];
        }
    } 
    
    // 4. PROFIT EXPORT
    elseif ($type === 'profit') {
        $rows = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('drugs', 'sale_items.drug_id', '=', 'drugs.id')
            ->select(
                DB::raw('DATE(sales.created_at) as date'),
                DB::raw('SUM(sale_items.total) as revenue'),
                DB::raw('SUM(drugs.cost_price * sale_items.quantity) as cost')
            )
            ->where('sales.status', 'completed')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($rows as $row) {
            $profit = $row->revenue - $row->cost;
            $margin = $row->revenue > 0 ? ($profit / $row->revenue) * 100 : 0;
            
            $data[] = [
                'Date' => $row->date,
                'Revenue' => number_format($row->revenue, 2, '.', ''),
                'Cost' => number_format($row->cost, 2, '.', ''),
                'Profit' => number_format($profit, 2, '.', ''),
                'Margin %' => number_format($margin, 1)
            ];
        }
    }

    // EXPORT LOGIC (CSV & PDF)
    if ($format === 'csv') {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
            }
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    } 
    
    elseif ($format === 'pdf') {
        $html = '<html><head><style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            h1 { text-align: center; color: #333; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #f3f4f6; padding: 8px; border: 1px solid #ddd; text-align: left; }
            td { padding: 8px; border: 1px solid #ddd; }
            .meta { text-align: center; color: #666; margin-bottom: 30px; }
        </style></head><body>';
        
        $html .= '<h1>' . ucfirst($type) . ' Report</h1>';
        $html .= '<div class="meta">Generated on: ' . now()->format('d M, Y H:i') . '</div>';
        
        if (!empty($data)) {
            $html .= '<table>';
            $html .= '<thead><tr>';
            foreach (array_keys($data[0]) as $header) {
                $html .= '<th>' . $header . '</th>';
            }
            $html .= '</tr></thead>';
            
            $html .= '<tbody>';
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . $cell . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p style="text-align:center;">No data found for this period.</p>';
        }
        
        $html .= '</body></html>';
        
        return response($html)
                ->header('Content-Type', 'text/html') 
                ->header('Content-Disposition', "attachment; filename={$filename}.html"); 
    }

    return redirect()->back();
}
}