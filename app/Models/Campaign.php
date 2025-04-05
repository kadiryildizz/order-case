<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'customer_id' => 'int',
        'status' => 'int',
        'total' => 'float',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'discount',
        'discount_type',
        'product_count',
        'count_type',
        'count_apply',
        'minimum_cart_total',
        'main_id',
        'main_type',
        'status',
        'criterions',
        'active_since',
        'active_till',
    ];
}