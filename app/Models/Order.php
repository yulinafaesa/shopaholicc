<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Order extends Model
{
    public const TRACKING_STEPS = [
        'pesanan_diterima',
        'barang_dibeli',
        'tiba_gudang',
        'dalam_pengiriman',
        'tiba_indonesia',
        'selesai',
    ];

    public const TRACKING_LABELS = [
        'pesanan_diterima' => 'Pesanan Diterima',
        'barang_dibeli' => 'Barang Dibeli',
        'tiba_gudang' => 'Tiba di Gudang',
        'dalam_pengiriman' => 'Dalam Pengiriman',
        'tiba_indonesia' => 'Tiba di Indonesia',
        'selesai' => 'Selesai',
    ];

    public const TRACKING_STATUS_TEXT = [
        'pesanan_diterima' => 'Pesanan Diterima — Menunggu Proses',
        'barang_dibeli' => 'Barang Sedang Dibeli di Luar Negeri',
        'tiba_gudang' => 'Barang Tiba di Gudang Luar Negeri',
        'dalam_pengiriman' => 'Dalam Pengiriman ke Indonesia',
        'tiba_indonesia' => 'Tiba di Indonesia — Proses Bea Cukai',
        'selesai' => 'Pesanan Selesai — Siap Diterima',
    ];

    protected $fillable = [
        'user_id',
        'order_number',
        'payment_method',
        'recipient_name',
        'address',
        'city',
        'postal_code',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'tracking_status',
        'service_name',
        'destination_country',
        'source_country',
        'last_tracking_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'shipping_cost' => 'integer',
            'total' => 'integer',
            'last_tracking_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(OrderTrackingEvent::class)->orderByDesc('occurred_at');
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'TYA'.random_int(100000000, 999999999);
        } while (self::query()->where('order_number', $number)->exists());

        return $number;
    }

    public function trackingStepIndex(): int
    {
        $index = array_search($this->tracking_status, self::TRACKING_STEPS, true);

        return $index === false ? 0 : $index;
    }

    public function formatDateTime($date = null): string
    {
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }
        $date ??= $this->created_at;
        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-';
    }

    public function toTrackingArray(): array
    {
        $this->loadMissing(['items', 'trackingEvents']);

        $currentIndex = $this->trackingStepIndex();

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'ordered_at' => $this->formatDateTime($this->created_at),
            'service_name' => $this->service_name,
            'destination_country' => $this->destination_country,
            'source_country' => $this->source_country,
            'total_formatted' => Product::formatRupiah($this->total),
            'payment_method' => $this->payment_method === 'transfer_bank' ? 'Transfer Bank' : 'E-wallet',
            'tracking_status' => $this->tracking_status,
            'tracking_status_label' => self::TRACKING_LABELS[$this->tracking_status] ?? $this->tracking_status,
            'tracking_status_text' => self::TRACKING_STATUS_TEXT[$this->tracking_status] ?? $this->tracking_status,
            'last_updated' => $this->formatDateTime($this->last_tracking_at ?? $this->updated_at),
            'steps' => collect(self::TRACKING_STEPS)->map(fn (string $step, int $index) => [
                'key' => $step,
                'label' => self::TRACKING_LABELS[$step],
                'done' => $index <= $currentIndex,
                'active' => $index === $currentIndex,
            ])->values()->all(),
            'history' => $this->trackingEvents->map(fn (OrderTrackingEvent $event) => [
                'status' => $event->status,
                'title' => $event->title,
                'location' => $event->location,
                'description' => $event->description,
                'occurred_at' => ($event->occurred_at instanceof \Illuminate\Support\Carbon ? $event->occurred_at : \Illuminate\Support\Carbon::parse($event->occurred_at))->timezone('Asia/Jakarta')->format('d M Y H:i'),
            ])->values()->all(),
            'items' => $this->items->map(fn (OrderItem $item) => [
                'name' => $item->product_name,
                'quantity' => $item->quantity,
                'line_total_formatted' => Product::formatRupiah($item->line_total),
            ])->values()->all(),
        ];
    }
}
