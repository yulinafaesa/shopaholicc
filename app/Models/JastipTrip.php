<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JastipTrip extends Model
{
    public const STATUS_PERSIAPAN = 'persiapan';

    public const STATUS_SEDANG_DI_PERJALANAN = 'sedang_di_perjalanan';

    public const STATUS_OPEN_PO = 'open_po';

    public const STATUS_CHECKOUT = 'checkout';

    public const STATUS_SUDAH_KEMBALI = 'sudah_kembali';

    protected $fillable = [
        'title',
        'origin_city',
        'origin_country',
        'destination_city',
        'destination_country',
        'status',
        'departure_date',
        'transit_city',
        'transit_date',
        'estimated_return_date',
        'order_deadline',
        'titip_estimation',
        'origin_lat',
        'origin_lng',
        'destination_lat',
        'destination_lng',
        'transit_lat',
        'transit_lng',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'transit_date' => 'date',
            'estimated_return_date' => 'date',
            'order_deadline' => 'datetime',
            'origin_lat' => 'float',
            'origin_lng' => 'float',
            'destination_lat' => 'float',
            'destination_lng' => 'float',
            'transit_lat' => 'float',
            'transit_lng' => 'float',
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PERSIAPAN => 'Persiapan',
            self::STATUS_SEDANG_DI_PERJALANAN => 'Sedang di Perjalanan',
            self::STATUS_OPEN_PO => 'Open PO',
            self::STATUS_CHECKOUT => 'Checkout',
            self::STATUS_SUDAH_KEMBALI => 'Sudah Kembali',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public function isCurrent(): bool
    {
        return in_array($this->status, [
            self::STATUS_SEDANG_DI_PERJALANAN,
            self::STATUS_OPEN_PO,
            self::STATUS_CHECKOUT,
        ], true);
    }

    /** @return array<int, array{lat: float, lng: float, label: string, type: string}> */
    public function routePoints(): array
    {
        $points = [
            [
                'lat' => (float) $this->origin_lat,
                'lng' => (float) $this->origin_lng,
                'label' => "{$this->origin_city}, {$this->origin_country}",
                'type' => 'origin',
            ],
        ];

        if ($this->transit_city && $this->transit_lat && $this->transit_lng) {
            $points[] = [
                'lat' => (float) $this->transit_lat,
                'lng' => (float) $this->transit_lng,
                'label' => $this->transit_city,
                'type' => 'transit',
            ];
        }

        $points[] = [
            'lat' => (float) $this->destination_lat,
            'lng' => (float) $this->destination_lng,
            'label' => "{$this->destination_city}, {$this->destination_country}",
            'type' => 'destination',
        ];

        return $points;
    }

    /** @return array<string, mixed> */
    public function toFrontendArray(): array
    {
        $depDate = is_string($this->departure_date) ? \Illuminate\Support\Carbon::parse($this->departure_date) : $this->departure_date;
        $transitDate = is_string($this->transit_date) ? \Illuminate\Support\Carbon::parse($this->transit_date) : $this->transit_date;
        $estReturnDate = is_string($this->estimated_return_date) ? \Illuminate\Support\Carbon::parse($this->estimated_return_date) : $this->estimated_return_date;
        $orderDeadline = is_string($this->order_deadline) ? \Illuminate\Support\Carbon::parse($this->order_deadline) : $this->order_deadline;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'origin_city' => $this->origin_city,
            'origin_country' => $this->origin_country,
            'destination_city' => $this->destination_city,
            'destination_country' => $this->destination_country,
            'status' => $this->status,
            'status_label' => self::statusLabel($this->status),
            'departure_date' => $depDate?->format('d M Y'),
            'transit_city' => $this->transit_city,
            'transit_date' => $transitDate?->format('d M Y'),
            'estimated_return_date' => $estReturnDate?->format('d M Y'),
            'order_deadline' => $orderDeadline?->format('d M Y, H:i'),
            'order_deadline_iso' => $orderDeadline?->toIso8601String(),
            'titip_estimation' => $this->titip_estimation,
            'route_points' => $this->routePoints(),
            'updated_at' => $this->updated_at instanceof \Illuminate\Support\Carbon ? $this->updated_at->diffForHumans() : \Illuminate\Support\Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
