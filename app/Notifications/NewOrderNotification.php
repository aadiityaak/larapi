<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

use Illuminate\Support\Facades\Log;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $order;

    /**
     * Buat instance notifikasi baru.
     *
     * Ambil pesan dari pengaturan.
     */
    public function __construct($order)
    {
        // Ambil pesan dari pengaturan
        $this->message = Setting::where('setting_key', 'new_order')->value('setting_value') ?? 'Pesan default jika tidak ada setting';

        // Simpan informasi order
        $this->order = $order;

        $this->message = str_replace(
            [
                '[nama_klien]',
                '[tanggal_order]',
                '[no_telp]',
                '[alamat]',
                '[order_number]',
                '[product]',
                '[tim_manajemen]',
            ],
            [
                $order->customer->name,
                $order->order_date,
                $order->customer->phone,
                $order->customer->alamat,
                $order->order_number,
                $order->product->name . ' (' . $order->product->category . ')',
                'Tim Manajemen ' . Setting::where('setting_key', 'app_name')->value('setting_value'),
            ],
            $this->message
        );
    }

    /**
     * Tentukan saluran mana yang akan digunakan untuk mengirim notifikasi.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Anda bisa menambahkan saluran lain seperti database, broadcast, dsb.
    }

    /**
     * Siapkan pesan email untuk notifikasi.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notifikasi Pesanan Baru')
            ->line($this->message)
            ->line('Tanggal Pesanan: ' . $this->order->order_date)
            ->action('Lihat Pesanan', url('/orders'))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Siapkan pesan database untuk notifikasi.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Pesanan baru telah diterima dari ' . $this->order->customer->name,
            'notifiable' => $notifiable,
        ];
    }
}
