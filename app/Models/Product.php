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
    'category',
  ];

  public function metaProducts()
  {
    return $this->hasMany(MetaProduct::class, 'product_id');
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
