<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public $timestamps = false;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi dinamis: jika order_code dimulai dengan SET-, maka product_id dianggap furniture_set_id
    public function item()
    {
        return $this->belongsTo(Product::class, 'product_id')
            ->when($this->order && str_starts_with($this->order->order_code, 'SET-'), function ($query) {
                // Override dengan relasi ke FurnitureSet jika prefix SET-
                $this->setRelation('item', $this->furnitureSet); // cache manual
            });
    }

    // Relasi eksplisit ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi eksplisit ke FurnitureSet
    public function furnitureSet()
    {
        return $this->belongsTo(FurnitureSet::class, 'product_id');
    }
}