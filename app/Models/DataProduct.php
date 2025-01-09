<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProduct extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'data_product';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'data_id',
    'product_id',
  ];

  /**
   * Get the related data.
   */
  public function data()
  {
    return $this->belongsTo(Data::class);
  }

  /**
   * Get the related product.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
