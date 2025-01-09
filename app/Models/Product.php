<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'price',
    'description',
    'data',
  ];

  public function dataProducts()
  {
    return $this->hasMany(DataProduct::class, 'product_id');
  }

  public function order()
  {
    return $this->belongsTo(Order::class);
  }
}
