<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'alamat',
        'kategori',
        'pekerjaan',
        'sertifikat',
        'nilai_transaksi',
        'harga_real',
        'harga_kesepakatan',
        'data_pajak_pembeli',
        'data_pajak_penjual',
    ];
}
