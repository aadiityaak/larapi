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

        // Insert 5 perorangan orders (individual customers)
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
            $order->meta = $orderMeta;
            $order->save();

            // Add Perorangan meta for these individual customers
            $customer->meta()->create([
                'meta_key' => 'bank',
                'meta_value' => 'Perorangan'
            ]);
        }

        // Insert bank orders (15 orders with bank customers)
        $bankOrders = [
            // BPR NUSAMBA PUSAT orders
            ['no_order' => 'BP001', 'product_id' => 2, 'bank' => 'BPR NUSAMBA PUSAT', 'customer_name' => 'PT. MITRA SEJAHTERA', 'customer_phone' => '02745551234', 'customer_address' => 'Jl. Sudirman No. 123, Yogyakarta'],
            ['no_order' => 'BP002', 'product_id' => 1, 'bank' => 'BPR NUSAMBA PUSAT', 'customer_name' => 'CV. JAYA ABADI', 'customer_phone' => '02745551123', 'customer_address' => 'Jl. Malioboro No. 56, Yogyakarta'],
            ['no_order' => 'BP003', 'product_id' => 3, 'bank' => 'BPR NUSAMBA PUSAT', 'customer_name' => 'PT. BERKAH MULYA', 'customer_phone' => '02745551345', 'customer_address' => 'Jl. Solo Km 12, Yogyakarta'],

            // BPR BBA orders
            ['no_order' => 'BB001', 'product_id' => 2, 'bank' => 'BPR BBA', 'customer_name' => 'PT. MAJU JAYA', 'customer_phone' => '027433344567', 'customer_address' => 'Jl. Kaliurang Km 5, Sleman'],
            ['no_order' => 'BB002', 'product_id' => 1, 'bank' => 'BPR BBA', 'customer_name' => 'CV. KARYA MANDIRI', 'customer_phone' => '027433344578', 'customer_address' => 'Jl. Gejayan No. 88, Yogyakarta'],

            // BPR PALA WNO orders
            ['no_order' => 'PW001', 'product_id' => 3, 'bank' => 'BPR PALA WNO', 'customer_name' => 'PT. INDO TEKNIK', 'customer_phone' => '027422233890', 'customer_address' => 'Jl. Ring Road Utara, Sleman'],
            ['no_order' => 'PW002', 'product_id' => 4, 'bank' => 'BPR PALA WNO', 'customer_name' => 'CV. SENTOSA ABADI', 'customer_phone' => '027422233901', 'customer_address' => 'Jl. Magelang Km 10, Yogyakarta'],

            // BPR ARUM MANDIRI orders
            ['no_order' => 'AM001', 'product_id' => 2, 'bank' => 'BPR ARUM MANDIRI', 'customer_name' => 'PT. MAHKOTA INDAH', 'customer_phone' => '027411122345', 'customer_address' => 'Jl. Wates Km 8, Bantul'],
            ['no_order' => 'AM002', 'product_id' => 1, 'bank' => 'BPR ARUM MANDIRI', 'customer_name' => 'CV. CAHAYA MULIA', 'customer_phone' => '027411122456', 'customer_address' => 'Jl. Parangtritis Km 5, Bantul'],

            // BPR NUSAMBA BANTUL orders
            ['no_order' => 'NB001', 'product_id' => 4, 'bank' => 'BPR NUSAMBA BANTUL', 'customer_name' => 'PT. BANTUL SEJAHTERA', 'customer_phone' => '027500998765', 'customer_address' => 'Jl. Bantul Km 3, Bantul'],
            ['no_order' => 'NB002', 'product_id' => 3, 'bank' => 'BPR NUSAMBA BANTUL', 'customer_name' => 'CV. NUSANTARA JAYA', 'customer_phone' => '027500998876', 'customer_address' => 'Jl. Imogiri Timur Km 10, Bantul'],

            // BPR DANAMAS PRIMA orders
            ['no_order' => 'DP001', 'product_id' => 2, 'bank' => 'BPR DANAMAS PRIMA', 'customer_name' => 'PT. DANAMAS INVESTAMA', 'customer_phone' => '027466677890', 'customer_address' => 'Jl. Godean Km 9, Sleman'],
            ['no_order' => 'DP002', 'product_id' => 1, 'bank' => 'BPR DANAMAS PRIMA', 'customer_name' => 'CV. PRIMA KARYA', 'customer_phone' => '027466677901', 'customer_address' => 'Jl. Palagan Km 7, Yogyakarta'],

            // KOPERASI ARTHA KENCANA orders
            ['no_order' => 'AK001', 'product_id' => 3, 'bank' => 'KOPERASI ARTHA KENCANA', 'customer_name' => 'KOPERASI KARYA TANI', 'customer_phone' => '027477788012', 'customer_address' => 'Jl. Kaliurang Km 15, Sleman'],
            ['no_order' => 'AK002', 'product_id' => 4, 'bank' => 'KOPERASI ARTHA KENCANA', 'customer_name' => 'KOPKAR BERSATU', 'customer_phone' => '027477788123', 'customer_address' => 'Jl. Seturan Raya, Sleman'],
        ];

        // Insert bank orders with bank customers
        foreach ($bankOrders as $orderData) {
            // Extract order data
            $orderInfo = [
                'no_order' => $orderData['no_order'],
                'order_date' => '2025-11-25',
                'product_id' => $orderData['product_id'],
                'price' => rand(50000000, 500000000),
                'payment_method' => 'Transfer',
                'paid' => 0,
                'created_at' => '2025-11-25T11:00:00.000000Z',
                'updated_at' => '2025-11-25T11:00:00.000000Z'
            ];

            // Create or find bank customer
            $customer = Customer::firstOrCreate(
                ['phone' => $orderData['customer_phone']],
                [
                    'name' => $orderData['customer_name'],
                    'address' => $orderData['customer_address'],
                    'created_at' => '2025-11-25T10:30:00.000000Z',
                    'updated_at' => '2025-11-25T10:30:00.000000Z'
                ]
            );

            // Create order
            $order = $customer->orders()->create($orderInfo);

            // Create basic order meta data for bank orders
            $order->meta = [
                '2' => rand(100, 999) . '/2025',
                '3' => '2025-11-24T17:00:00.000Z',
                '5' => rand(100000000, 400000000)
            ];
            $order->save();

            // Add bank meta to customer
            $customer->meta()->create([
                'meta_key' => 'bank',
                'meta_value' => $orderData['bank']
            ]);
        }
    }
}
