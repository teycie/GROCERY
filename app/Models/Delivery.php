<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    public const DELIVERY_STATUSES = ['pending', 'approved', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'];
    public const PICKUP_STATUSES = ['pending', 'approved', 'preparing', 'ready', 'ready_to_pickup', 'picked_up', 'cancelled'];

    protected $fillable = [
        'order_id',
        'user_id',
        'seller_id',
        'product_id',
        'quantity',
        'fulfillment_type',
        'payment_mode',
        'status',
        'tracking_status',
        'address',
        'estimated_date',
        'delivered_date',
        'notes',
    ];

    protected $dates = ['estimated_date', 'delivered_date'];

    public static function statusesFor(?string $fulfillmentType): array
    {
        return $fulfillmentType === 'pickup' ? self::PICKUP_STATUSES : self::DELIVERY_STATUSES;
    }

    public function getTrackingStatusAttribute($value)
    {
        return $value ?: $this->status;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getProgressPercentageAttribute()
    {
        $deliveryProgress = [
            'pending' => 0,
            'approved' => 15,
            'processing' => 35,
            'shipped' => 60,
            'out_for_delivery' => 85,
            'delivered' => 100,
            'cancelled' => 0,
        ];

        $pickupProgress = [
            'pending' => 0,
            'approved' => 20,
            'preparing' => 45,
            'ready' => 70,
            'ready_to_pickup' => 90,
            'picked_up' => 100,
            'cancelled' => 0,
        ];

        $currentStatus = $this->tracking_status;
        $progressMap = $this->fulfillment_type === 'pickup' ? $pickupProgress : $deliveryProgress;

        return $progressMap[$currentStatus] ?? 0;
    }

    public function getStatusBadgeColorAttribute()
    {
        switch ($this->tracking_status) {
            case 'pending':
                return 'gray';
            case 'approved':
                return 'emerald';
            case 'processing':
            case 'preparing':
                return 'yellow';
            case 'shipped':
            case 'ready':
                return 'blue';
            case 'out_for_delivery':
            case 'ready_to_pickup':
                return 'purple';
            case 'delivered':
            case 'picked_up':
                return 'green';
            case 'cancelled':
                return 'red';
            default:
                return 'gray';
        }
    }
}
