<?php

namespace Module\Product\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'name',
        'price_in_cents',
        'photo'
    ];

    public static function newFactory(): ProductFactory
    {
        return new ProductFactory();
    }
}
