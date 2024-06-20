<?php
declare(strict_types=1);

namespace Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Order\Models\Order;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'complement',
        'neighborhood',
        'zipcode',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function newFactory(): CustomerFactory
    {
        return new CustomerFactory();
    }
}
