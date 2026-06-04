<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'price', 'category', 'image', 'shipping_from', 'is_active'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function formattedPrice(): string
    {
        return self::formatRupiah($this->price);
    }

    public static function formatRupiah($amount): string
    {
        return 'Rp. '.number_format((int) $amount, 0, ',', '.');
    }
}
