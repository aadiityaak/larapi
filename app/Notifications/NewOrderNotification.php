<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class NewOrderNotification extends Notification
{
    protected $message;
    protected $order;

    /**
     * Buat instance notifikasi baru.
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
                '[address]',
                '[order_number]',
                '[product]',
                '[tim_manajemen]',
            ],
            [
                $order->customer->name,
                $order->order_date,
                $order->customer->phone,
                $order->customer->address,
                $order->order_number,
                $order->product->name . ' (' . $order->product->category . ')',
                'Tim Manajemen ' . Setting::where('setting_key', 'app_name')->value('setting_value'),
            ],
            $this->message
        );
    }

    /**
     * Tentukan saluran mana yang akan digunakan untuk mengirim notifikasi.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Siapkan pesan email untuk notifikasi.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notifikasi Pesanan Baru')
            ->view('emails.new_order', [
                'messageContent' => $this->message, // Ubah key agar tidak bentrok
                'order' => $this->order
            ]);
    }

    /**
     * Siapkan pesan database untuk notifikasi.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Pesanan baru telah diterima dari ' . $this->order->customer->name,
            'notifiable' => $notifiable,
        ];
    }
}
