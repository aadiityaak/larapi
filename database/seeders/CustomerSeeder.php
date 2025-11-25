<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [
            [
                'name' => 'Nyonya TRI WHYDHARTI',
                'phone' => '082264079363',
                'address' => 'Baciro Sanggrahan Gondokusuman 4/105 Yogyakarta, Rukun Tetangga 042, Rukun Warga 011, Kelurahan Baciro, Kecamatan Gondokusuman, Kota Yogyakarta',
                'created_at' => '2025-11-24T08:04:08.000000Z',
                'updated_at' => '2025-11-24T08:04:08.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA KK PIYUNGAN']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00555',
                        'order_date' => '2025-11-24',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '86/2025',
                            '3' => '2025-11-23T17:00:00.000Z',
                            '5' => '75000000',
                            '7' => '2028-11-23T17:00:00.000Z',
                            '8' => 'HM 12345/Baciro',
                            '9' => '3404010107890',
                            '10' => '34.71.100.004.018-0045.0',
                            '11' => 'Nyonya TRI WHYDHARTI',
                            '14' => 93750000,
                            '33' => 'HM 11678/Baciro'
                        ],
                        'created_at' => '2025-11-24T08:30:00.000000Z',
                        'updated_at' => '2025-11-24T08:30:00.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya MUJIYEM',
                'phone' => '081804275558',
                'address' => 'Burikan, RT 005, RW 005, Desa Sumberadi, Kecamatan Mlati, Kabupaten Sleman',
                'created_at' => '2025-11-24T07:06:24.000000Z',
                'updated_at' => '2025-11-24T07:06:24.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR BBA']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00554',
                        'order_date' => '2025-11-24',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '85',
                            '3' => '2025-11-23T17:00:00.000Z',
                            '5' => '50000000',
                            '9' => '13040605.09816',
                            '10' => '34.04.060.002.020-0152.0',
                            '11' => 'Nyonya MUJIYEM',
                            '14' => 62500000,
                            '33' => 'HM 10569/SUMBERADI'
                        ],
                        'created_at' => '2025-11-24T07:07:39.000000Z',
                        'updated_at' => '2025-11-24T07:07:39.000000Z'
                    ],
                    [
                        'no_order' => 'AN00556',
                        'order_date' => '2025-11-24',
                        'product_id' => 1,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '87',
                            '3' => '2025-11-23T17:00:00.000Z',
                            '5' => '30000000',
                            '7' => '2026-11-23T17:00:00.000Z',
                            '8' => 'SHM NIB 13.02.000012345.0',
                            '9' => '13040605.09817',
                            '10' => '34.04.060.002.020-0153.0',
                            '11' => 'Nyonya MUJIYEM',
                            '14' => 37500000,
                            '33' => 'HM 10570/SUMBERADI'
                        ],
                        'created_at' => '2025-11-24T08:45:00.000000Z',
                        'updated_at' => '2025-11-24T08:45:00.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan BUDI WIDODO',
                'phone' => '085868542340',
                'address' => 'Butuh, RT 001, RW 001, Desa Muruh, Kecamatan Gantiwarno, Kabupaten Klaten',
                'created_at' => '2025-11-24T07:02:18.000000Z',
                'updated_at' => '2025-11-24T07:02:18.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR BBA']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00553',
                        'order_date' => '2025-11-24',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '84',
                            '3' => '2025-11-23T17:00:00.000Z',
                            '5' => '180000000',
                            '9' => '11.19.02.09.00655',
                            '10' => '33.10.020.011.007-0066.0',
                            '11' => 'Tuan BUDI WIDODO',
                            '14' => 225000000,
                            '33' => 'HM 01614/MURUH'
                        ],
                        'created_at' => '2025-11-24T07:04:08.000000Z',
                        'updated_at' => '2025-11-24T07:04:08.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan ANDI TRISETIAWAN',
                'phone' => '088233435561',
                'address' => 'Bobung, Rukun Tetangga 009, Rukun Warga 002, Desa Putat, Kecamatan Patuk, Kabupaten Gunungkidul',
                'created_at' => '2025-11-21T09:23:54.000000Z',
                'updated_at' => '2025-11-21T09:23:54.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR PALA WNO']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00551',
                        'order_date' => '2025-11-21',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '170/2025',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '35000000',
                            '7' => '2028-11-20T17:00:00.000Z',
                            '8' => 'SHM NIB 13.02.000009471.0',
                            '9' => '-',
                            '10' => '34.03.100.006.008-0323.0',
                            '11' => 'ANDI TRISETIAWAN',
                            '12' => '-',
                            '14' => 50000000,
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-21T09:26:43.000000Z',
                        'updated_at' => '2025-11-21T09:26:43.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan YOYOK SUPRIADI',
                'phone' => '085784857779',
                'address' => 'Jlagran Gedongtengen 2/351, Rukun Tetangga 015, Rukun Warga 003, Kelurahan Pringgokusuman, Kecamatan Gedongtengen, Kota Yogyakarta',
                'created_at' => '2025-11-21T09:22:34.000000Z',
                'updated_at' => '2025-11-21T09:22:34.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'KOPERASI ARTHA KENCANA']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00552',
                        'order_date' => '2025-11-21',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '82',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '50000000',
                            '8' => null,
                            '9' => '13.05.000003240.0',
                            '10' => '34.71.120.001.001-0127.0',
                            '11' => 'YOYOK SUPRIADI',
                            '14' => 65000000
                        ],
                        'created_at' => '2025-11-21T09:27:29.000000Z',
                        'updated_at' => '2025-11-21T09:27:29.000000Z'
                    ],
                    [
                        'no_order' => 'AN00557',
                        'order_date' => '2025-11-21',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '88',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '25000000',
                            '17' => 'BPKB Nomor: D-12345678',
                            '18' => '45000000',
                            '19' => 'YOYOK SUPRIADI',
                            '20' => '45000000',
                            '21' => '3401234567890123'
                        ],
                        'created_at' => '2025-11-21T10:15:00.000000Z',
                        'updated_at' => '2025-11-21T10:15:00.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya MUNAH',
                'phone' => '085790665374',
                'address' => 'Jalan Grinjing Nomor 17 Papringan, Rukun Tetangga 017, Rukun Warga 006, Desa Caturtunggal, Kecamatan Depok, Kabupaten Sleman',
                'created_at' => '2025-11-21T09:16:16.000000Z',
                'updated_at' => '2025-11-21T09:16:16.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00550',
                        'order_date' => '2025-11-21',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '83',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '13500000',
                            '17' => 'BPKB Nomor: R-01906175 I',
                            '18' => '19500000',
                            '19' => 'MUNAH',
                            '20' => '19500000',
                            '21' => '3308207112940006',
                            '55' => '835/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-21T09:19:31.000000Z',
                        'updated_at' => '2025-11-21T09:19:31.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan SUGIYARTO',
                'phone' => '083173486696',
                'address' => 'Sambirejo, Rukun Tetangga 005, Rukun Warga 004, Desa Semanu, Kecamatan Semanu, Kabupaten Gunungkidul',
                'created_at' => '2025-11-21T09:12:29.000000Z',
                'updated_at' => '2025-11-21T09:12:29.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR ARUM MANDIRI']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00549',
                        'order_date' => '2025-11-21',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '167/2025',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '10000000',
                            '7' => '2027-05-18T17:00:00.000Z',
                            '8' => '02664/Semanu',
                            '9' => '1302050502509',
                            '10' => '34.03.050.005.009-0047.0',
                            '11' => 'TUKILAH',
                            '12' => '-',
                            '13' => '-',
                            '14' => 87421000,
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-21T09:18:43.000000Z',
                        'updated_at' => '2025-11-21T09:18:43.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan DRS. ISKAK RUSMADI',
                'phone' => '081804329151',
                'address' => 'Sokokerep, Rukun Tetangga 007, Rukun Warga 049, Desa Semanu, Kecamatan Semanu, Kabupaten Gunungkidul',
                'created_at' => '2025-11-21T09:06:12.000000Z',
                'updated_at' => '2025-11-21T09:06:12.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR ARUM MANDIRI']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00548',
                        'order_date' => '2025-11-21',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '81',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '5000000',
                            '17' => 'BPKB O-07842719 I',
                            '18' => '9800000',
                            '19' => 'DRS ISKAK RUSMADI',
                            '20' => '9800000',
                            '21' => '696120393545',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-21T09:09:18.000000Z',
                        'updated_at' => '2025-11-21T09:09:18.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan GATOT WUKIR SARTOPO',
                'phone' => '081802735959',
                'address' => 'Pacing Kidul, Rukun Tetangga 005, Rukun Warga 023, Desa Pacarejo, Kecamatan Semanu, Kabupaten Gunungkidul',
                'created_at' => '2025-11-21T09:01:33.000000Z',
                'updated_at' => '2025-11-21T09:01:33.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR ARUM MANDIRI']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00547',
                        'order_date' => '2025-11-21',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '74',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '20000000',
                            '17' => 'BPKB V-06238878',
                            '18' => '71400000',
                            '19' => 'ELISA FEBRIANA',
                            '20' => '71400000',
                            '21' => '959384611545000',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-21T09:03:48.000000Z',
                        'updated_at' => '2025-11-21T09:03:48.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya Ir. MARSIYATI',
                'phone' => '081328156455',
                'address' => 'Nitiprayan, RT 002, RW 000, Desa Ngestiharjo, Kecamatan Kasihan, Kabupaten Bantul',
                'created_at' => '2025-11-21T05:40:17.000000Z',
                'updated_at' => '2025-11-21T05:40:17.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR BBA']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00546',
                        'order_date' => '2025-11-21',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '80',
                            '3' => '2025-11-20T17:00:00.000Z',
                            '5' => '225000000',
                            '9' => '13010304.25614',
                            '10' => '34.02.150.001.017-0602.0',
                            '11' => 'Ir. MARSIYATI',
                            '14' => 281250000,
                            '33' => 'HM. 21314/Bangunjiwo'
                        ],
                        'created_at' => '2025-11-21T05:42:00.000000Z',
                        'updated_at' => '2025-11-21T05:42:00.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan AFANDI NUGROHO',
                'phone' => '+62 87775757493',
                'address' => 'Kedaton, Rukun Tetangga 001, Rukun Warga 000, Desa Pleret, Kecamatan Pleret, Kabupaten Bantul',
                'created_at' => '2025-11-19T09:27:55.000000Z',
                'updated_at' => '2025-11-19T09:27:55.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR DANAMAS PRIMA']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00545',
                        'order_date' => '2025-11-19',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '73',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '20000000',
                            '17' => 'BPKB Nomor: Q-01258649 I',
                            '18' => '75000000',
                            '19' => 'MEILINA DUIKORINA',
                            '20' => '75000000',
                            '21' => '-',
                            '55' => '832/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-19T09:29:35.000000Z',
                        'updated_at' => '2025-11-19T09:29:35.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya YUNIARTI',
                'phone' => '081393717662',
                'address' => 'Pajangan, Rukun Tetangga 003, Rukun Warga 010, Desa Sendangtirto, Kecamatan Berbah, Kabupaten Sleman',
                'created_at' => '2025-11-19T09:01:16.000000Z',
                'updated_at' => '2025-11-19T09:01:16.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00544',
                        'order_date' => '2025-11-19',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '70',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '45000000',
                            '17' => 'BPKB Nomor: M-12286246',
                            '18' => '75000000',
                            '19' => 'SIGIT',
                            '20' => '75000000',
                            '21' => '3471125005690002',
                            '55' => '830/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-19T09:03:16.000000Z',
                        'updated_at' => '2025-11-19T09:03:16.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan TUKIMAN',
                'phone' => '085878069250',
                'address' => 'Temuwuh, RT 003, RW 000, Desa Temuruh, Kecamatan Dlingo, Kabupaten Bantul',
                'created_at' => '2025-11-19T08:26:12.000000Z',
                'updated_at' => '2025-11-19T08:26:12.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR PALA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00543',
                        'order_date' => '2025-11-19',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '72',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '260000000',
                            '8' => 'HM. 00821/Temuwuh',
                            '9' => '13.01.17.04.00466',
                            '10' => '34.02.100.004.011-0183.0',
                            '11' => 'Haris Pujianto Alias Tukiman',
                            '14' => 50000000
                        ],
                        'created_at' => '2025-11-19T08:27:55.000000Z',
                        'updated_at' => '2025-11-19T08:27:55.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya SUMARNI',
                'phone' => '08',
                'address' => 'Grogol 6, Rukun Tetangga 007, Rukun Warga 006, Desa Bejiharjo, Kecamatan Karangmojo, Kabupaten Gunungkidul',
                'created_at' => '2025-11-19T08:02:17.000000Z',
                'updated_at' => '2025-11-19T08:02:17.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR ARUM MANDIRI']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00542',
                        'order_date' => '2025-11-19',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '64',
                            '3' => '2025-11-17T17:00:00.000Z',
                            '5' => '9000000',
                            '17' => 'FC BPKB P-01862335 I',
                            '18' => '13500000',
                            '19' => 'SLAMET',
                            '20' => '13500000',
                            '21' => '-',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-19T08:04:11.000000Z',
                        'updated_at' => '2025-11-19T08:04:11.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya NINING PUSPITASARI',
                'phone' => '081328405191',
                'address' => 'Nglebak, Rukun Tetangga 009, Rukun Warga -, Desa Palbapang, Kecamatan Bantul, Kabupaten Bantul',
                'created_at' => '2025-11-19T07:58:08.000000Z',
                'updated_at' => '2025-11-19T07:58:08.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA BANTUL']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00541',
                        'order_date' => '2025-11-19',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '71',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '25000000',
                            '17' => 'BPKB Nomor: O-07875341',
                            '18' => '115000000',
                            '19' => 'NINING PUSPITASARI',
                            '20' => '115000000',
                            '21' => '3402086909790005',
                            '55' => '831/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-19T08:02:27.000000Z',
                        'updated_at' => '2025-11-19T08:02:27.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan KALIS SULIA ATMOJO',
                'phone' => '085234273005',
                'address' => 'Sumbermulyo, Rukun Tetangga 004, Rukun Warga 003, Desa Kepek, Kecamatan Wonosari, Kabupaten Gunungkidul',
                'created_at' => '2025-11-19T07:56:29.000000Z',
                'updated_at' => '2025-11-19T07:56:29.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR PALA WNO']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00540',
                        'order_date' => '2025-11-19',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '166/2025',
                            '3' => '2025-11-18T17:00:00.000Z',
                            '5' => '40000000',
                            '7' => '2028-11-18T17:00:00.000Z',
                            '8' => '00679/Karangrejek',
                            '9' => '1302080700701',
                            '10' => '34.03.080.007.016-0055.0',
                            '11' => 'SAMIRAN',
                            '12' => '-',
                            '13' => 'A0 102431',
                            '14' => 60000000,
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-19T07:59:45.000000Z',
                        'updated_at' => '2025-11-19T07:59:45.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya HARTINI',
                'phone' => '083854230787',
                'address' => 'Gunting, Rukun Tetangga 0003, Rukun Warga -, Desa Gilangharjo, Kecamatan Pandak, Kabupaten Bantul',
                'created_at' => '2025-11-17T06:11:52.000000Z',
                'updated_at' => '2025-11-17T06:11:52.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA BANTUL']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00538',
                        'order_date' => '2025-11-17',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '59',
                            '3' => '2025-11-16T17:00:00.000Z',
                            '5' => '12000000',
                            '17' => 'BPKB Nomor: S-01417439 I',
                            '18' => '20000000',
                            '19' => 'HERI KURNIAWAN',
                            '20' => '20000000',
                            '21' => '-',
                            '55' => '824/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-17T06:13:04.000000Z',
                        'updated_at' => '2025-11-17T06:13:04.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan SUPRIYADI',
                'phone' => '081252240395',
                'address' => 'Deresan Dukuh Deresan, Rukun Tetangga 002, Rukun Warga -, Desa Ringin Harjo, Kecamatan Bantul, Kabupaten Bantul',
                'created_at' => '2025-11-17T06:08:18.000000Z',
                'updated_at' => '2025-11-17T06:08:18.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA BANTUL']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00537',
                        'order_date' => '2025-11-17',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '58',
                            '3' => '2025-11-16T17:00:00.000Z',
                            '5' => '35000000',
                            '17' => 'BPKB Nomor: Q-01227826',
                            '18' => '100000000',
                            '19' => 'INTAN PRIDANI PUTRI PRASTIWI',
                            '20' => '100000000',
                            '21' => '-',
                            '55' => '823/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-17T06:09:42.000000Z',
                        'updated_at' => '2025-11-17T06:09:42.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'MURTONO',
                'phone' => '081328610235',
                'address' => 'Glagah, Rukun Tetangga 006, Rukun Warga 006, Desa Kemiri, Kecamatan Tanjungsari, Kabupaten Gunungkidul',
                'created_at' => '2025-11-15T07:43:09.000000Z',
                'updated_at' => '2025-11-15T07:43:09.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR PALA WNO']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00536',
                        'order_date' => '2025-11-14',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '5' => '100000000',
                            '10' => '34.03.070.001.001-0017.0',
                            '11' => 'MURTONO',
                            '13' => 'A8643216',
                            '14' => 60000000,
                            '33' => 'HM NIB 13.02.000030081.0',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-15T07:50:13.000000Z',
                        'updated_at' => '2025-11-15T07:51:49.000000Z'
                    ],
                    [
                        'no_order' => 'AN00535',
                        'order_date' => '2025-11-15',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '5' => '100000000',
                            '9' => '-',
                            '10' => '34.03.070.001.004-0004.0',
                            '11' => 'HARTO WIYONO',
                            '12' => '-',
                            '13' => 'AF 485263',
                            '14' => 90000000,
                            '33' => '00467/Bendungan',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-15T07:46:39.000000Z',
                        'updated_at' => '2025-11-15T07:46:39.000000Z'
                    ],
                    [
                        'no_order' => 'AN00558',
                        'order_date' => '2025-11-15',
                        'product_id' => 2,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '168/2025',
                            '3' => '2025-11-14T17:00:00.000Z',
                            '5' => '150000000',
                            '7' => '2029-11-14T17:00:00.000Z',
                            '8' => 'HM 00900/Kemiri',
                            '9' => '13.05.17.06.00789',
                            '10' => '34.03.070.001.005-0012.0',
                            '11' => 'MURTONO',
                            '14' => 187500000,
                            '33' => 'HM 00901/Kemiri'
                        ],
                        'created_at' => '2025-11-15T08:30:00.000000Z',
                        'updated_at' => '2025-11-15T08:30:00.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya MIPAH YULIANTI',
                'phone' => '081329805861',
                'address' => 'Soboman, RT 005, RW 000, Desa Ngestiharjo, Kecamatan Kasihan, Kabupaten Bantul',
                'created_at' => '2025-11-14T08:43:43.000000Z',
                'updated_at' => '2025-11-14T08:43:43.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA KK KADIPIRO']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00534',
                        'order_date' => '2025-11-14',
                        'product_id' => 26,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'Kesepakatan Jual Beli, Surat Kuasa Menjual',
                            '2' => '52 ; 53',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '350000000',
                            '19' => 'Nyonya MIPAH YULIANTI',
                            '20' => '525000000',
                            '30' => '2025-11-13T17:00:00.000Z',
                            '33' => 'HM 09346/Ngestiharjo',
                            '55' => '821/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-14T08:48:25.000000Z',
                        'updated_at' => '2025-11-14T08:48:25.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan FATAJI SUSIADI',
                'phone' => '089674435783',
                'address' => 'Jl. Maundri No. 6A, Rejokusuman, Sokowaten, RT 004, RW 000, Desa Tamanan, Kecamatan Banguntapan, Kabupaten Bantul',
                'created_at' => '2025-11-14T08:39:51.000000Z',
                'updated_at' => '2025-11-14T08:39:51.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00533',
                        'order_date' => '2025-11-13',
                        'product_id' => 26,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'Perjanjian Kredit ; Surat Kuasa Membebankan Hak Tanggungan',
                            '2' => '42 ; 43',
                            '3' => '2025-11-12T17:00:00.000Z',
                            '5' => '450000000',
                            '19' => 'FATAJI SUSIADI',
                            '20' => '675000000',
                            '30' => '2025-11-12T17:00:00.000Z',
                            '33' => 'HM 06396/Tamanan',
                            '55' => '815/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-14T08:42:46.000000Z',
                        'updated_at' => '2025-11-14T08:42:46.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan EKO SUHARTONO, Bachelor of Science',
                'phone' => '082220003546',
                'address' => 'Jomblangan, Rukun Tetangga 004, Rukun Warga -, Desa Banguntapan, Kecamatan Banguntapan, Kabupaten Bantul',
                'created_at' => '2025-11-14T08:38:43.000000Z',
                'updated_at' => '2025-11-14T08:38:43.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00532',
                        'order_date' => '2025-11-14',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '51',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '15000000',
                            '17' => 'BPKB Nomor: S-01341505',
                            '18' => '110000000',
                            '19' => 'LUCIA ETTY KRISTIANI',
                            '20' => '110000000',
                            '21' => '-',
                            '55' => '820/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-14T08:40:32.000000Z',
                        'updated_at' => '2025-11-14T08:40:32.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan ANDI PASKAH KURNIAWAN',
                'phone' => '08994565689',
                'address' => 'Kitren Kotagede 2/664, Rukun Tetangga 036, Rukun Warga 008, Kelurahan Prenggan, Kecamatan Kotagede, Kota Yogyakarta',
                'created_at' => '2025-11-14T08:35:51.000000Z',
                'updated_at' => '2025-11-14T08:35:51.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00531',
                        'order_date' => '2025-11-14',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '50',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '22000000',
                            '17' => '1. BPKB Nomor: R-01828236 I, 2. BPKB Nomor: N-10675133 I',
                            '18' => '38000000',
                            '19' => '1. WINARTI, S.Pd., 2. ANDREAS WIRATSONGKO',
                            '20' => '38000000',
                            '21' => '-',
                            '55' => '819/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-14T08:37:45.000000Z',
                        'updated_at' => '2025-11-14T08:37:45.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Nyonya VERONIKA ELYSA KURNIADEWI',
                'phone' => '08158791209',
                'address' => 'Perumahan Sendok Indah Kotagede II/373, Rukun Tetangga 020, Rukun Warga 004, Kelurahan Prenggan, Kecamatan Kotagede, Kota Yogyakarta',
                'created_at' => '2025-11-14T08:26:09.000000Z',
                'updated_at' => '2025-11-14T08:26:09.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA PUSAT']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00530',
                        'order_date' => '2025-11-14',
                        'product_id' => 25,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'Perjanjian Pengikatan Jaminan Fidusia & Perjanjian Kredit Notariil',
                            '2' => '55',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '85000000',
                            '17' => 'BPKB Nomor: P-02805918 I',
                            '18' => '220000000',
                            '19' => 'VERONIKA ELYSA KURNIADEWI',
                            '20' => '220000000',
                            '21' => '-',
                            '33' => 'BPKB Nomor: P-02805918 I',
                            '55' => '-'
                        ],
                        'created_at' => '2025-11-14T08:28:41.000000Z',
                        'updated_at' => '2025-11-14T08:28:41.000000Z'
                    ]
                ]
            ],
            [
                'name' => 'Tuan NGADIMIN',
                'phone' => '0819 1462 7639',
                'address' => 'Warak Kidul, Rukun Tetangga 003, Rukun Warga 009, Desa Sumberadi, Kecamatan Mlati, Kabupaten Sleman.',
                'created_at' => '2025-11-14T04:14:51.000000Z',
                'updated_at' => '2025-11-14T04:14:51.000000Z',
                'meta' => [
                    ['meta_key' => 'bank', 'meta_value' => 'BPR NUSAMBA KK PIYUNGAN']
                ],
                'orders' => [
                    [
                        'no_order' => 'AN00529',
                        'order_date' => '2025-11-14',
                        'product_id' => 4,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '1' => 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA',
                            '2' => '49',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '30000000',
                            '17' => 'BPKB Nomor: K-03108372 I',
                            '18' => '100000000',
                            '19' => 'UTI HARTATI',
                            '20' => '100000000',
                            '21' => '-',
                            '55' => '818/ZPW/LEG/XI/2025'
                        ],
                        'created_at' => '2025-11-14T04:16:19.000000Z',
                        'updated_at' => '2025-11-14T04:16:19.000000Z'
                    ],
                    [
                        'no_order' => 'AN00559',
                        'order_date' => '2025-11-14',
                        'product_id' => 1,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '2' => '56',
                            '3' => '2025-11-13T17:00:00.000Z',
                            '5' => '120000000',
                            '7' => '2027-11-13T17:00:00.000Z',
                            '8' => 'SHM NIB 13.02.000045678.0',
                            '9' => '1302080601234',
                            '10' => '34.03.080.007.017-0056.0',
                            '11' => 'NGADIMIN',
                            '14' => 150000000,
                            '33' => 'HM 01234/Sumberadi'
                        ],
                        'created_at' => '2025-11-14T09:30:00.000000Z',
                        'updated_at' => '2025-11-14T09:30:00.000000Z'
                    ],
                    [
                        'no_order' => 'AN00560',
                        'order_date' => '2025-11-14',
                        'product_id' => 3,
                        'price' => 0,
                        'payment_method' => 'Transfer',
                        'paid' => 0,
                        'meta' => [
                            '5' => '80000000',
                            '9' => '1302080601235',
                            '10' => '34.03.080.007.018-0057.0',
                            '11' => 'NGADIMIN',
                            '13' => 'A7654321',
                            '14' => 100000000,
                            '33' => 'HM 01235/Sumberadi'
                        ],
                        'created_at' => '2025-11-14T10:45:00.000000Z',
                        'updated_at' => '2025-11-14T10:45:00.000000Z'
                    ]
                ]
            ],
        ];

        // Insert specific customers with their meta and order data
        foreach ($customers as $customerData) {
            $meta = $customerData['meta'] ?? [];
            $orders = $customerData['orders'] ?? [];
            unset($customerData['meta']);
            unset($customerData['orders']);

            $customer = Customer::create($customerData);

            // Insert meta data for the customer
            foreach ($meta as $metaData) {
                $customer->meta()->create($metaData);
            }

            // Insert orders for the customer
            foreach ($orders as $orderData) {
                $orderMeta = $orderData['meta'] ?? [];
                unset($orderData['meta']);

                $order = $customer->orders()->create($orderData);

                // Insert meta data for the order
                if (!empty($orderMeta)) {
                    $order->meta = $orderMeta;
                    $order->save();
                }
            }
        }

        // Create additional random customers if needed
        Customer::factory(50)->create();
    }
}
