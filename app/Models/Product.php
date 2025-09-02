<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'category',
  ];

  public function metaProducts()
  {
    return $this->hasMany(MetaProduct::class, 'product_id');
  }

  // Relationship to get metas through pivot table with meta details
  public function metas()
  {
    return $this->belongsToMany(Meta::class, 'meta_product', 'product_id', 'meta_id')
                ->select(['metas.id', 'metas.name', 'metas.type']);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
