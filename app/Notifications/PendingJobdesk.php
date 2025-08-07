<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting;

class PendingJobdesk extends Notification
{
    protected $message;
    protected $data;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct($data)
    {
        // Ambil pesan dari pengaturan, jika ada
        $this->message = Setting::where('setting_key', 'pending_jobdesk')->value('setting_value') ?? 'Pesan default jika tidak ada setting';

        // Simpan data jobdesk
        $this->data = $data;

        $this->message = str_replace(
            [
                '[nama_klien]',
                '[jobdesk_id]',
                '[tim_manajemen]',
            ],
            [
                $data['client_name'],
                $data['jobdesk_id'],
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
        $channels = ['database'];
        
        // Hanya kirim email jika user mengaktifkan notifikasi email
        if ($notifiable->email_notifications ?? true) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Siapkan pesan email untuk notifikasi.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pemberitahuan: Jobdesk Belum Diambil')
            ->view('emails.pending_jobdesk', [
                'messageContent' => $this->message,
                'data' => $this->data,
                'notifiable' => $notifiable,
            ]);
    }

    /**
     * Siapkan pesan database untuk notifikasi.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Jobdesk #' . $this->data['jobdesk_id'] . ' belum diambil oleh ' . $notifiable->name,
            'notifiable' => $notifiable,
            'jobdesk' => $this->data
        ];
    }
}
