<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'product_id',
        'price',
        'payment_method',
        'paid',
        'data',
        'lampiran',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }
}
