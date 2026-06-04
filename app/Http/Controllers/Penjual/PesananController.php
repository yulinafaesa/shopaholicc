<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class PesananController extends Controller
{
    public function index(): Response
    {
        try {
            $orders = Order::query()
                ->with(['user:id,name,email', 'items'])
                ->latest()
                ->get()
                ->map(fn (Order $order) => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer' => $order->user?->name ?? '-',
                    'email' => $order->user?->email ?? '-',
                    'total_formatted' => Product::formatRupiah($order->total),
                    'tracking_status' => $order->tracking_status,
                    'tracking_status_label' => Order::TRACKING_LABELS[$order->tracking_status] ?? $order->tracking_status,
                    'items_count' => $order->items->count(),
                    'ordered_at' => $order->formatDateTime($order->created_at),
                ])
                ->values()
                ->all();

            return Inertia::render('penjual/Pesanan', [
                'orders' => $orders,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Pesanan index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return Inertia::render('penjual/Pesanan', [
                'orders' => [],
                'error' => $e->getMessage() . ' (Line: ' . $e->getLine() . ' in ' . basename($e->getFile()) . ')',
            ]);
        }
    }
}
