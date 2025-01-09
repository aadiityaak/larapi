<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    /** @use HasFactory<\Database\Factories\DataFactory> */
    use HasFactory;

    protected $table = 'data';

    protected $fillable = [
        'name',
        'value',
        'type'
    ];

    public function dataProducts()
    {
        return $this->hasMany(DataProduct::class, 'data_id');
    }
}
