<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected static $appCode;

    protected static function boot()
    {
        parent::boot();

        self::$appCode = Setting::where('setting_key', 'app_code')->value('setting_value') ?? 'AN';

        static::creating(function ($model) {
            $lastOrder = self::where('no_order', 'like', self::$appCode . '%')
                ->orderBy('no_order', 'desc')
                ->first();

            // Mengambil angka dari no_order terakhir
            $newIdNumber = 1; // Default jika tidak ada order sebelumnya

            if ($lastOrder) {
                // Ambil angka dari no_order terakhir
                $lastId = substr($lastOrder->no_order, strlen(self::$appCode));
                $newIdNumber = intval($lastId) + 1; // Tambahkan 1
            }

            // Buat no_order baru
            $model->no_order = self::$appCode . str_pad($newIdNumber, 5, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'no_order',
        'customer_id',
        'pemberi_order',
        'order_date',
        'product_id',
        'price',
        'payment_method',
        'paid',
        'meta',
        'lampiran',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function jobdesks()
    {
        return $this->hasMany(Jobdesk::class);
    }
}
