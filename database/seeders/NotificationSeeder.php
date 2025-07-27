<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;

class NotificationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Hapus data notifikasi lama jika ada
    DB::table('notifications')->truncate();

    // Ambil sample users dan orders
    $users = User::take(5)->get();
    $orders = Order::take(10)->get();

    if ($users->isEmpty() || $orders->isEmpty()) {
      $this->command->warn('Tidak ada user atau order untuk membuat notifikasi. Jalankan UserSeeder dan OrderSeeder terlebih dahulu.');
      return;
    }

    $notifications = [];
    $now = Carbon::now();

    // Template notifikasi untuk berbagai jenis
    $notificationTemplates = [
      // New Order Notifications
      [
        'type' => 'App\Notifications\NewOrderNotification',
        'data' => [
          'title' => 'Order Baru Masuk',
          'message' => 'Ada order baru yang perlu ditangani',
          'order_id' => null, // akan diisi dinamis
          'order_no' => null, // akan diisi dinamis
          'customer_name' => null, // akan diisi dinamis
          'action_url' => '/orders/',
          'icon' => 'bell',
          'type' => 'new_order'
        ]
      ],
      // Pending Jobdesk Notifications
      [
        'type' => 'App\Notifications\PendingJobdesk',
        'data' => [
          'title' => 'Tugas Menunggu Dikerjakan',
          'message' => 'Ada tugas yang belum dikerjakan dan mendekati deadline',
          'jobdesk_id' => null, // akan diisi dinamis
          'order_id' => null, // akan diisi dinamis
          'deadline' => null, // akan diisi dinamis
          'action_url' => '/jobdesk/',
          'icon' => 'clock',
          'type' => 'pending_jobdesk'
        ]
      ],
      // System Notifications
      [
        'type' => 'App\Notifications\SystemNotification',
        'data' => [
          'title' => 'Selamat Datang di Sistem',
          'message' => 'Akun Anda telah berhasil dibuat dan diaktifkan',
          'action_url' => '/dashboard',
          'icon' => 'user-check',
          'type' => 'welcome'
        ]
      ],
      [
        'type' => 'App\Notifications\SystemNotification',
        'data' => [
          'title' => 'Backup Database Berhasil',
          'message' => 'Backup database harian telah berhasil dilakukan',
          'action_url' => '/settings',
          'icon' => 'database',
          'type' => 'backup_success'
        ]
      ],
      [
        'type' => 'App\Notifications\SystemNotification',
        'data' => [
          'title' => 'Update Sistem Tersedia',
          'message' => 'Ada update sistem terbaru yang tersedia untuk diinstall',
          'action_url' => '/system/updates',
          'icon' => 'download',
          'type' => 'system_update'
        ]
      ]
    ];

    // Buat notifikasi untuk setiap user
    foreach ($users as $user) {
      // Buat 3-5 notifikasi per user
      $notificationCount = rand(3, 5);

      for ($i = 0; $i < $notificationCount; $i++) {
        $template = $notificationTemplates[array_rand($notificationTemplates)];
        $order = $orders->random();

        // Sesuaikan data berdasarkan jenis notifikasi
        $data = $template['data'];

        if ($template['type'] === 'App\Notifications\NewOrderNotification') {
          $data['order_id'] = $order->id;
          $data['order_no'] = $order->no_order;
          $data['customer_name'] = $order->customer->name ?? 'Customer';
          $data['action_url'] = '/orders/' . $order->id;
        } elseif ($template['type'] === 'App\Notifications\PendingJobdesk') {
          $data['order_id'] = $order->id;
          $data['jobdesk_id'] = rand(1, 50); // Random jobdesk ID
          $data['deadline'] = $now->copy()->addDays(rand(1, 7))->format('Y-m-d H:i:s');
          $data['action_url'] = '/jobdesk/' . $data['jobdesk_id'];
        }

        // Tentukan status read/unread (80% unread, 20% read)
        $isRead = rand(1, 100) <= 20;

        $notifications[] = [
          'id' => \Illuminate\Support\Str::uuid(),
          'type' => $template['type'],
          'notifiable_type' => 'App\Models\User',
          'notifiable_id' => $user->id,
          'data' => json_encode($data),
          'read_at' => $isRead ? $now->copy()->subDays(rand(1, 30))->format('Y-m-d H:i:s') : null,
          'created_at' => $now->copy()->subDays(rand(0, 30))->subHours(rand(0, 23))->format('Y-m-d H:i:s'),
          'updated_at' => $now->format('Y-m-d H:i:s'),
        ];
      }
    }

    // Tambahkan beberapa notifikasi broadcast untuk semua user
    $broadcastNotifications = [
      [
        'title' => 'Maintenance Sistem Terjadwal',
        'message' => 'Sistem akan menjalani maintenance pada hari Minggu jam 02:00 - 04:00 WIB',
        'icon' => 'tools',
        'type' => 'maintenance',
        'action_url' => '/announcements'
      ],
      [
        'title' => 'Pelatihan Sistem Baru',
        'message' => 'Akan ada pelatihan penggunaan fitur baru sistem pada hari Jumat',
        'icon' => 'graduation-cap',
        'type' => 'training',
        'action_url' => '/training'
      ]
    ];

    foreach ($broadcastNotifications as $broadcast) {
      foreach ($users as $user) {
        $notifications[] = [
          'id' => \Illuminate\Support\Str::uuid(),
          'type' => 'App\Notifications\SystemNotification',
          'notifiable_type' => 'App\Models\User',
          'notifiable_id' => $user->id,
          'data' => json_encode($broadcast),
          'read_at' => rand(1, 100) <= 30 ? $now->copy()->subDays(rand(1, 5))->format('Y-m-d H:i:s') : null,
          'created_at' => $now->copy()->subDays(rand(1, 7))->format('Y-m-d H:i:s'),
          'updated_at' => $now->format('Y-m-d H:i:s'),
        ];
      }
    }

    // Insert semua notifikasi ke database
    DB::table('notifications')->insert($notifications);

    $this->command->info('Berhasil membuat ' . count($notifications) . ' notifikasi untuk ' . $users->count() . ' user');
  }
}
