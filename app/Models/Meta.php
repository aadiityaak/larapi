<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /** @use HasFactory<\Database\Factories\DataFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'type'
    ];

    public function metaProducts()
    {
        return $this->hasMany(MetaProduct::class, 'meta_id');
    }
}
