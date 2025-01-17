<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting; // Import model Setting

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Buat instance notifikasi baru.
     *
     * Ambil pesan dari pengaturan.
     */
    public function __construct()
    {
        // Ambil pesan dari pengaturan
        $this->message = Setting::where('setting_key', 'new_order')->value('setting_value') ?? 'Pesan default jika tidak ada setting';
    }

    /**
     * Tentukan saluran mana yang akan digunakan untuk mengirim notifikasi.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Anda bisa menambahkan saluran lain seperti database, broadcast, dsb.
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
            ->action('Lihat Pesanan', url('/orders')) // Sesuaikan URL sesuai kebutuhan Anda
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }
}
