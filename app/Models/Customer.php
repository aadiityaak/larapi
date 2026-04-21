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
        'address',
    ];

    protected $appends = [
        'phones',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function meta()
    {
        return $this->hasMany(CustomerMeta::class);
    }

    // Accessor untuk mendapatkan semua nomor telepon
    public function getPhonesAttribute()
    {
        $phones = [];
        
        // Tambahkan nomor utama
        if ($this->phone) {
            $phones[] = [
                'id' => 'primary',
                'phone' => $this->phone,
                'is_primary' => true,
            ];
        }

        // Tambahkan nomor tambahan dari meta
        $additionalPhones = $this->meta()
            ->where('meta_key', 'like', 'phone_%')
            ->get();

        foreach ($additionalPhones as $meta) {
            $phones[] = [
                'id' => $meta->id,
                'phone' => $meta->meta_value,
                'is_primary' => false,
            ];
        }

        return $phones;
    }

    // Method untuk menyimpan nomor telepon tambahan
    public function saveAdditionalPhones($phones)
    {
        // Hapus nomor tambahan lama
        $this->meta()->where('meta_key', 'like', 'phone_%')->delete();

        // Simpan nomor baru
        if (is_array($phones)) {
            $index = 1;
            foreach ($phones as $phoneData) {
                // Skip nomor utama
                if (isset($phoneData['is_primary']) && $phoneData['is_primary']) {
                    continue;
                }
                
                if (!empty($phoneData['phone'])) {
                    $this->meta()->create([
                        'meta_key' => 'phone_' . $index++,
                        'meta_value' => $phoneData['phone'],
                    ]);
                }
            }
        }
    }
}
