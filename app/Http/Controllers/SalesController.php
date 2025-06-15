<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated supplier
        $supplier = Auth::user()->supplier;
        
        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier profile not found.');
        }

        // Get filter parameters
        $month = $request->get('month', now()->format('Y-m'));
        $year = $request->get('year', now()->year);
        
        // Parse the month filter
        if ($month) {
            $filterDate = Carbon::parse($month . '-01');
        } else {
            $filterDate = now()->startOfMonth();
        }

        // Base query for supplier's orders
        $ordersQuery = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup']);

        // Apply month filter
        if ($month) {
            $ordersQuery->whereYear('created_at', $filterDate->year)
                       ->whereMonth('created_at', $filterDate->month);
        }

        // Get orders with related data
        $orders = $ordersQuery->with(['customer.user', 'orderItems.material', 'orderItems.materialVariation'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $totalQuantitySold = $orders->sum(function($order) {
            return $order->orderItems->sum('quantity');
        });

        // Get top selling materials with images
        $topMaterials = OrderItem::whereHas('order', function($query) use ($supplier, $filterDate, $month) {
                $query->where('supplier_id', $supplier->id)
                      ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup']);
                
                if ($month) {
                    $query->whereYear('created_at', $filterDate->year)
                          ->whereMonth('created_at', $filterDate->month);
                }
            })
            ->with(['material.images' => function($query) {
                $query->orderBy('order')->limit(1);
            }])
            ->select('material_id', 'material_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_revenue')
            ->groupBy('material_id', 'material_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Get daily sales for chart (last 30 days or current month)
        $dailySales = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup'])
            ->when($month, function($query) use ($filterDate) {
                return $query->whereYear('created_at', $filterDate->year)
                           ->whereMonth('created_at', $filterDate->month);
            }, function($query) {
                return $query->where('created_at', '>=', now()->subDays(30));
            })
            ->select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total) as daily_revenue')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Get monthly sales for the current year vs previous year comparison
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;
        
        $currentYearSales = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup'])
            ->whereYear('created_at', $currentYear)
            ->select(DB::raw('MONTH(created_at) as month'))
            ->selectRaw('SUM(total) as monthly_revenue')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('monthly_revenue', 'month')
            ->toArray();

        $previousYearSales = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup'])
            ->whereYear('created_at', $previousYear)
            ->select(DB::raw('MONTH(created_at) as month'))
            ->selectRaw('SUM(total) as monthly_revenue')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('monthly_revenue', 'month')
            ->toArray();

        // Prepare monthly comparison data
        $monthlyComparison = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyComparison[] = [
                'month' => Carbon::create()->month($i)->format('M'),
                'current_year' => $currentYearSales[$i] ?? 0,
                'previous_year' => $previousYearSales[$i] ?? 0,
            ];
        }

        // Get monthly sales for the year
        $monthlySales = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup'])
            ->whereYear('created_at', $year)
            ->select(DB::raw('MONTH(created_at) as month'))
            ->selectRaw('COUNT(*) as order_count')
            ->selectRaw('SUM(total) as monthly_revenue')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Get available months and years for filter
        $availableMonths = Order::where('supplier_id', $supplier->id)
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'value' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                    'label' => Carbon::create($item->year, $item->month)->format('F Y')
                ];
            });

        return view('supplier.sales.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'totalQuantitySold',
            'topMaterials',
            'monthlyComparison',
            'currentYear',
            'previousYear',
            'month',
            'year',
            'availableMonths',
            'filterDate'
        ));
    }

    public function export(Request $request)
    {
        $supplier = Auth::user()->supplier;
        
        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier profile not found.');
        }

        $month = $request->get('month', now()->format('Y-m'));
        $filterDate = Carbon::parse($month . '-01');

        // Get orders for export
        $orders = Order::where('supplier_id', $supplier->id)
            ->whereIn('order_status', ['completed', 'shipped', 'to_ship', 'preparing_to_pickup', 'ready_to_pickup'])
            ->whereYear('created_at', $filterDate->year)
            ->whereMonth('created_at', $filterDate->month)
            ->with(['customer.user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'sales_report_' . $filterDate->format('Y_m') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Order ID',
                'Customer Name',
                'Order Date',
                'Delivery Method',
                'Order Status',
                'Material Name',
                'Variation',
                'Quantity',
                'Unit Price',
                'Subtotal',
                'Shipping Cost',
                'Total'
            ]);

            // Add data rows
            foreach ($orders as $order) {
                foreach ($order->orderItems as $item) {
                    fputcsv($file, [
                        $order->order_id,
                        $order->customer->user->username,
                        $order->created_at->format('Y-m-d H:i:s'),
                        ucfirst(str_replace('_', ' ', $order->delivery_method)),
                        ucfirst(str_replace('_', ' ', $order->order_status)),
                        $item->material_name,
                        $item->variation_name ? $item->variation_name . ': ' . $item->variation_value : 'N/A',
                        $item->quantity,
                        'RM' . number_format($item->unit_price, 2),
                        'RM' . number_format($item->subtotal, 2),
                        'RM' . number_format($order->shipping_cost, 2),
                        'RM' . number_format($order->total, 2)
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}