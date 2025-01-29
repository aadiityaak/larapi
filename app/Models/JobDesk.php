<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jobdesk extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'description',
        'tanggal_pengerjaan',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_pengerjaan' => 'date', // Automatic casting to date
        'tanggal_selesai' => 'date', // Automatic casting to date
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
