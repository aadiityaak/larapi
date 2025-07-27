# Notification Seeder Documentation

## File yang Dibuat

### 1. NotificationSeeder.php

-   **Lokasi**: `database/seeders/NotificationSeeder.php`
-   **Fungsi**: Membuat data dummy notifikasi untuk testing dan development

## Fitur NotificationSeeder

### Jenis Notifikasi yang Dibuat:

1. **New Order Notification** (`App\Notifications\NewOrderNotification`)

    - Notifikasi order baru masuk
    - Data: order_id, order_no, customer_name, action_url

2. **Pending Jobdesk** (`App\Notifications\PendingJobdesk`)

    - Notifikasi tugas yang pending/mendekati deadline
    - Data: jobdesk_id, order_id, deadline, action_url

3. **System Notification** (`App\Notifications\SystemNotification`)
    - Welcome message
    - Backup database berhasil
    - Update sistem tersedia
    - Maintenance terjadwal
    - Pelatihan sistem

### Karakteristik Data:

-   **Jumlah**: 3-5 notifikasi per user + 2 broadcast notification untuk semua user
-   **Status Read**: 20% sudah dibaca, 80% belum dibaca (realistic scenario)
-   **Tanggal**: Random dalam 30 hari terakhir
-   **UUID**: Menggunakan UUID untuk primary key
-   **JSON Data**: Data notifikasi disimpan dalam format JSON

### Struktur Data Notifikasi:

```json
{
    "title": "Judul Notifikasi",
    "message": "Pesan notifikasi",
    "action_url": "/path/to/action",
    "icon": "icon-name",
    "type": "notification_type",
    "order_id": 123,
    "customer_name": "Nama Customer"
}
```

## Cara Penggunaan

### Jalankan Seeder Individual:

```bash
php artisan db:seed --class=NotificationSeeder
```

### Jalankan Semua Seeder (termasuk NotificationSeeder):

```bash
php artisan db:seed
```

### Reset dan Seed Ulang:

```bash
php artisan migrate:fresh --seed
```

## Statistik Hasil Seeder

Berdasarkan hasil terakhir:

-   **Total notifikasi**: 29
-   **Belum dibaca**: 24 (83%)
-   **Sudah dibaca**: 5 (17%)

## Dependencies

Seeder ini membutuhkan:

-   User model dengan data user
-   Order model dengan data order dan customer
-   Tabel notifications (migrasi sudah ada)

## Integration dengan DatabaseSeeder

NotificationSeeder sudah ditambahkan ke `DatabaseSeeder.php` dan akan dijalankan setelah:

-   Users dibuat
-   Customers dan Orders dibuat
-   Semua data master sudah tersedia

Hal ini memastikan referensi data (user_id, order_id) valid saat membuat notifikasi.
