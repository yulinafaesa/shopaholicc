<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        try {
            $totalOrders = Order::query()->count();
            $activeProducts = Product::query()->count();
            $totalRevenue = (int) Order::query()->sum('total');
            $totalCustomers = User::query()->where('role', 'pembeli')->count();

            $recentOrders = Order::query()
                ->with('user:id,name,email')
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn (Order $order) => [
                    'order_number' => $order->order_number,
                    'customer' => $order->user?->name ?? '-',
                    'total_formatted' => Product::formatRupiah($order->total),
                    'status_label' => Order::TRACKING_LABELS[$order->tracking_status] ?? $order->tracking_status,
                    'ordered_at' => $order->formatDateTime($order->created_at),
                ])
                ->values()
                ->all();

            return Inertia::render('penjual/Dashboard', [
                'stats' => [
                    'total_orders' => $totalOrders,
                    'active_products' => $activeProducts,
                    'total_revenue' => Product::formatRupiah($totalRevenue),
                    'total_customers' => $totalCustomers,
                ],
                'recent_orders' => $recentOrders,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return Inertia::render('penjual/Dashboard', [
                'stats' => [
                    'total_orders' => 0,
                    'active_products' => 0,
                    'total_revenue' => 'Rp. 0',
                    'total_customers' => 0,
                ],
                'recent_orders' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}
