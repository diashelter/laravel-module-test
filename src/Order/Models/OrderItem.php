<?php

namespace Module\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Product\Model\Product;

class OrderItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'amount_in_cents',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory(): OrderItemFactory
    {
        return new OrderItemFactory();
    }
}

