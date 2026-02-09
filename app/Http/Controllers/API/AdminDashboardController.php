<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\HistoryEntry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $salesOverTime = Order::query()
            ->select('order_date', DB::raw('SUM(total_amount) as total_sales'))
            ->whereDate('order_date', '>=', now()->subDays(30))
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->order_date,
                'total_sales' => (float) $row->total_sales,
            ]);

        $ordersByStatus = Order::query()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $topProducts = OrderItem::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.line_total) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'name' => $row->name,
                'total_revenue' => (float) $row->total_revenue,
            ]);

        $recentActivity = HistoryEntry::query()
            ->latest('service_date')
            ->limit(5)
            ->get([
                'order_number',
                'customer_name',
                'service_date',
                'notes',
            ]);

        return Response::json([
            'total_sales' => Order::sum('total_amount'),
            'total_orders' => Order::count(),
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'orders_by_status' => $ordersByStatus,
            'sales_over_time' => $salesOverTime,
            'top_products' => $topProducts,
            'recent_activity' => $recentActivity,
        ]);
    }
}
