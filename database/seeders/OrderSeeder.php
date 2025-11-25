<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $peroranganOrders = [
            [
                'no_order' => 'AN00561',
                'order_date' => '2025-11-25',
                'product_id' => 1,
                'price' => 0,
                'payment_method' => 'Transfer',
                'paid' => 0,
                'customer' => [
                    'name' => 'Tuan AHMAD RIZKI',
                    'phone' => '081223345678',
                    'address' => 'Jl. Malioboro No. 45, Gedongtengen, Kecamatan Gondokusuman, Kota Yogyakarta'
                ],
                'meta' => [
                    '1' => 'Akta Jual Beli',
                    '2' => '89/2025',
                    '3' => '2025-11-24T17:00:00.000Z',
                    '5' => '250000000',
                    '7' => '2026-11-24T17:00:00.000Z',
                    '8' => 'SHM NIB 13.02.000056789.0',
                    '9' => '3404010123456',
                    '10' => '34.71.100.004.019-0056.0',
                    '11' => 'AHMAD RIZKI',
                    '12' => '-',
                    '14' => 312500000,
                    '33' => 'HM 23456/Gedongtengen'
                ]
            ],
            [
                'no_order' => 'AN00562',
                'order_date' => '2025-11-25',
                'product_id' => 2,
                'price' => 0,
                'payment_method' => 'Transfer',
                'paid' => 0,
                'customer' => [
                    'name' => 'Nyonya SITI NURHALIZAH',
                    'phone' => '087654321098',
                    'address' => 'Perumahan Griya Citra Asri Blok A No. 12, Condongcatur, Kecamatan Depok, Kabupaten Sleman'
                ],
                'meta' => [
                    '2' => '90/2025',
                    '3' => '2025-11-24T17:00:00.000Z',
                    '5' => '150000000',
                    '7' => '2027-11-24T17:00:00.000Z',
                    '8' => 'HM 34567/Condongcatur',
                    '9' => '1302050701234',
                    '10' => '34.03.080.007.019-0067.0',
                    '11' => 'SITI NURHALIZAH',
                    '14' => 187500000,
                    '33' => 'HM 34568/Condongcatur'
                ]
            ],
            [
                'no_order' => 'AN00563',
                'order_date' => '2025-11-25',
                'product_id' => 3,
                'price' => 0,
                'payment_method' => 'Transfer',
                'paid' => 0,
                'customer' => [
                    'name' => 'Tuan BAMBANG SUGIANTO',
                    'phone' => '082345678901',
                    'address' => 'Jl. Parangtritis Km 8, Sewon, Bantul, DIY'
                ],
                'meta' => [
                    '5' => '300000000',
                    '9' => '1301030456789',
                    '10' => '34.02.150.001.018-0089.0',
                    '11' => 'BAMBANG SUGIANTO',
                    '13' => 'A1234567',
                    '14' => 375000000,
                    '33' => 'HM 456789/Sewon'
                ]
            ],
            [
                'no_order' => 'AN00564',
                'order_date' => '2025-11-25',
                'product_id' => 4,
                'price' => 0,
                'payment_method' => 'Transfer',
                'paid' => 0,
                'customer' => [
                    'name' => 'Nyonya DEWI LESTARI',
                    'phone' => '089876543210',
                    'address' => 'Jl. Kaliurang Km 10, Ngaglik, Sleman, DIY'
                ],
                'meta' => [
                    '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                    '2' => '91',
                    '3' => '2025-11-24T17:00:00.000Z',
                    '5' => '50000000',
                    '17' => 'BPKB Nomor: AB-12345678',
                    '18' => '85000000',
                    '19' => 'DEWI LESTARI',
                    '20' => '85000000',
                    '21' => '3401023456789012'
                ]
            ],
            [
                'no_order' => 'AN00565',
                'order_date' => '2025-11-25',
                'product_id' => 1,
                'price' => 0,
                'payment_method' => 'Transfer',
                'paid' => 0,
                'customer' => [
                    'name' => 'Tuan SLAMET RAHARJO',
                    'phone' => '081234567890',
                    'address' => 'Jl. Wates Km 12, Pengasih, Kulon Progo, DIY'
                ],
                'meta' => [
                    '2' => '92/2025',
                    '3' => '2025-11-24T17:00:00.000Z',
                    '5' => '175000000',
                    '7' => '2028-11-24T17:00:00.000Z',
                    '8' => 'SHM NIB 13.02.000067890.0',
                    '9' => '3403010234567',
                    '10' => '34.01.020.003.012-0098.0',
                    '11' => 'SLAMET RAHARJO',
                    '14' => 218750000,
                    '33' => 'HM 567890/Pengasih'
                ]
            ]
        ];

        // Insert perorangan orders with customer and meta data
        foreach ($peroranganOrders as $orderData) {
            $customerData = $orderData['customer'];
            $orderMeta = $orderData['meta'] ?? [];

            // Extract order data (remove customer and meta)
            $orderInfo = [
                'no_order' => $orderData['no_order'],
                'order_date' => $orderData['order_date'],
                'product_id' => $orderData['product_id'],
                'price' => $orderData['price'],
                'payment_method' => $orderData['payment_method'],
                'paid' => $orderData['paid'],
                'created_at' => '2025-11-25T10:00:00.000000Z',
                'updated_at' => '2025-11-25T10:00:00.000000Z'
            ];

            unset($orderData['customer']);
            unset($orderData['meta']);

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['phone' => $customerData['phone']],
                [
                    'name' => $customerData['name'],
                    'address' => $customerData['address'],
                    'created_at' => '2025-11-25T09:00:00.000000Z',
                    'updated_at' => '2025-11-25T09:00:00.000000Z'
                ]
            );

            // Create order
            $order = $customer->orders()->create($orderInfo);

            // Create order meta data
            foreach ($orderMeta as $key => $value) {
                $order->meta()->create([
                    'meta_key' => $key,
                    'meta_value' => $value
                ]);
            }

            // Add bank meta to customer (random banks with full names)
            $banks = [
                'BPR NUSAMBA PUSAT',
                'BPR BBA',
                'BPR PALA WNO',
                'BPR ARUM MANDIRI',
                'BPR NUSAMBA BANTUL',
                'BPR DANAMAS PRIMA',
                'KOPERASI ARTHA KENCANA',
                'BPR NUSAMBA KK PIYUNGAN',
                'BPR NUSAMBA KK KADIPIRO'
            ];

            $customer->meta()->create([
                'meta_key' => 'bank',
                'meta_value' => $banks[array_rand($banks)]
            ]);
        }
    }
}
