<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;
    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'quantity' => 0,
        'unit_price' => 0,
        'discount' => 0,
        'total_price' => 0,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'product_id' => 'int',
        'order_id' => 'int',
        'quantity' => 'int',
        'unit_price' => 'float',
        'discount' => 'float',
        'total_price' => 'float',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'unit_price',
        'discount',
        'total_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
