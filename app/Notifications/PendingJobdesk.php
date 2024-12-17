<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingJobdesk extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pemberitahuan: Jobdesk Belum Diambil')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Kami ingin mengingatkan Anda bahwa ada jobdesk yang belum Anda ambil:')
            ->line('Jobdesk: ' . $this->data['jobdesk_id'])
            ->action('Ambil Jobdesk', url('/jobdesk/' . $this->data['jobdesk_id']))
            ->line('Terima kasih atas perhatian Anda!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'jobdesk' => $this->data['jobdesk_id'],
            'message' => 'Pemberitahuan: Jobdesk #' . $this->data['jobdesk_id'] . ' Belum Diambil'
        ];
    }
}
