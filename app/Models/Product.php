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

  public function getDataKeys()
  {
    // Memecah string data menjadi array
    $keys = explode(',', $this->data);

    // Mengambil data yang sesuai dengan key yang dipisahkan koma
    return Data::whereIn('key', $keys)->get();
  }
}
