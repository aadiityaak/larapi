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
        'service',
        'price',
        'payment_method',
        'paid',
        'document',
        'lampiran',
    ];

    protected $casts = [
        'document' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }
}
