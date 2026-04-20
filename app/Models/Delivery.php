<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'seller_id',
        'product_id',
        'quantity',
        'status',
        'address',
        'estimated_date',
        'delivered_date',
        'notes',
    ];

    protected $dates = ['estimated_date', 'delivered_date'];

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
        $statuses = ['pending' => 0, 'processing' => 25, 'shipped' => 50, 'out_for_delivery' => 75, 'delivered' => 100, 'cancelled' => 0];
        return $statuses[$this->status] ?? 0;
    }

    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'gray';
            case 'processing':
                return 'yellow';
            case 'shipped':
                return 'blue';
            case 'out_for_delivery':
                return 'purple';
            case 'delivered':
                return 'green';
            case 'cancelled':
                return 'red';
            default:
                return 'gray';
        }
    }
}
