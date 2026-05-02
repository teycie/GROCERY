<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_rider_available',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * A seller can have many products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * A buyer can have one cart record if you decide to persist carts later.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Deliveries assigned to this user as rider.
     */
    public function riderDeliveries()
    {
        return $this->hasMany(Delivery::class, 'rider_id');
    }

    /**
     * Delivery assignments for this rider.
     */
    public function deliveryAssignments()
    {
        return $this->hasMany(DeliveryAssignment::class, 'rider_id');
    }
}
