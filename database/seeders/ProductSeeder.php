<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            // 1–12 PRODUK UTAMA
            ['name' => 'Kaos Rebook', 'price' => 299000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/1200x/2c/22/dc/2c22dc1b682825de9b7e8e569a50e244.jpg'],
            ['name' => 'Dompet Stradivarius', 'price' => 350000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/1200x/c6/1e/e5/c61ee5f67a3c1c6febf4effc6842ad75.jpg'],
            ['name' => 'Croptop Bershka', 'price' => 400000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/1200x/1b/97/86/1b978657cbf2bf20f3b5b484f78a2cd2.jpg'],
            ['name' => 'Keychain Labubu', 'price' => 435000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/736x/c2/1e/65/c21e65ef36cf1a15fba2c5f5759ec4f8.jpg'],
            ['name' => 'Belt Calvin Klein', 'price' => 575000, 'category' => 'Aksesoris', 'image' => 'https://calvinklein-eu.scene7.com/is/image/CalvinKleinEU/K60K602141_910_main?$main@2x$'],
            ['name' => 'Tas Gentle Woman', 'price' => 620000, 'category' => 'Tas', 'image' => 'https://i.pinimg.com/1200x/8f/4c/43/8f4c43035991d043bea61e0c03ec0a87.jpg'],
            ['name' => 'Sepatu Nike', 'price' => 715000, 'category' => 'Sepatu', 'image' => 'https://i.pinimg.com/1200x/84/b7/6a/84b76a93fa5d68cf3b97b8fcd4b27f33.jpg'],
            ['name' => 'Kemeja Zara', 'price' => 480000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/736x/21/5b/60/215b6093b8db5176d8a4938afb436ee8.jpg'],
            ['name' => 'Jam Tangan Casio', 'price' => 890000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/736x/ee/24/64/ee2464e2fa1c56956ff0be97cb6bd708.jpg'],
            ['name' => 'Dress H&M', 'price' => 520000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/736x/24/65/3a/24653abd7c1fef71348ca50e14be88df.jpg'],
            ['name' => 'Topi New Era', 'price' => 310000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/1200x/e9/a5/a9/e9a5a9beced3daa28270d422fc7baedd.jpg'],
            ['name' => 'Sandal Adidas', 'price' => 650000, 'category' => 'Sepatu', 'image' => 'https://i.pinimg.com/1200x/f6/9d/cb/f69dcb399d28c01db1ee7859ce7dc4b3.jpg'],

            // 13–20 TAMBAHAN (SEMUA URL SUDAH FIX DARI KAMU)
            ['name' => 'Hoodie Nike', 'price' => 750000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/736x/84/d1/99/84d19930247eb05ffb4173083b056ca2.jpg'],
            ['name' => 'Sneakers Converse', 'price' => 680000, 'category' => 'Sepatu', 'image' => 'https://down-id.img.susercontent.com/file/sg-11134201-824im-mfdqvtdw5n2g71@resize_w900_nl.webp'],
            ['name' => 'Tote Bag Uniqlo', 'price' => 250000, 'category' => 'Tas', 'image' => 'https://i.pinimg.com/1200x/d5/3d/d3/d53dd3b9dab1f3806695dc42ab2ef86d.jpg'],
            ['name' => 'Parfum Dior', 'price' => 1200000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/1200x/cb/65/a6/cb65a68d751f5131406a5853206275e5.jpg'],
            ['name' => 'Kaos Oversize Streetwear', 'price' => 320000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/736x/ef/9e/d7/ef9ed7f7534eeb9b621e317fe9bf2606.jpg'],
            ['name' => 'Ransel Eiger', 'price' => 550000, 'category' => 'Tas', 'image' => 'https://i.pinimg.com/1200x/ff/f7/72/fff772c743c61fa316fab3788fef5ed6.jpg'],
            ['name' => 'Kacamata Rayban', 'price' => 900000, 'category' => 'Aksesoris', 'image' => 'https://i.pinimg.com/736x/15/85/1a/15851a575e2560af006fdf2ddb87c0d3.jpg'],
            ['name' => 'Jaket Denim', 'price' => 600000, 'category' => 'Fashion', 'image' => 'https://i.pinimg.com/1200x/70/b3/8c/70b38c90308c36ad4878abeb1607a776.jpg'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    ...$product,
                    'slug' => Str::slug($product['name']),
                    'category' => $product['category'] ?? 'Uncategorized',
                    'shipping_from' => $product['shipping_from'] ?? collect(['Jakarta', 'Bandung', 'Surabaya', 'Bali'])->random(),
                    'stock' => rand(5, 50),
                    'is_active' => true,
                ]
            );
        }
    }
}