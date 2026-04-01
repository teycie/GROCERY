<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'user_id',
    ];

    /**
     * Each product belongs to one seller.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A product can have many images.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
