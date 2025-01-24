<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaProduct extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'meta_product';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'meta_id',
    'product_id',
  ];

  /**
   * Get the related meta.
   */
  public function meta()
  {
    return $this->belongsTo(Meta::class);
  }

  /**
   * Get the related product.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
