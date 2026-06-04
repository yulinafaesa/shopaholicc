<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProdukController extends Controller
{
    public function index(): Response
    {
        try {
            $products = Product::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'price_formatted' => $product->formattedPrice(),
                    'shipping_from' => $product->shipping_from,
                    'is_active' => $product->is_active,
                ])
                ->values()
                ->all();

            return Inertia::render('penjual/Produk', [
                'products' => $products,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Produk index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return Inertia::render('penjual/Produk', [
                'products' => [],
                'error' => $e->getMessage() . ' (Line: ' . $e->getLine() . ' in ' . basename($e->getFile()) . ')',
            ]);
        }
    }
}
