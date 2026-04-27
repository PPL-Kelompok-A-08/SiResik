<?php

namespace App\Notifications;

use App\Models\PermintaanPenjemputan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public PermintaanPenjemputan $permintaan;
    public string $oldStatus;
    public string $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(PermintaanPenjemputan $permintaan, string $oldStatus, string $newStatus)
    {
        $this->permintaan = $permintaan;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $link = route('permintaan-penjemputan.index');
        
        return (new MailMessage)
            ->subject('Status Permintaan Penjemputan Berubah')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Status permintaan penjemputan Anda telah berubah.')
            ->line('Alamat: ' . $this->permintaan->alamat)
            ->line('Status Lama: ' . $this->oldStatus)
            ->line('Status Baru: ' . $this->newStatus)
            ->action('Lihat Detail', $link)
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'permintaan_id' => $this->permintaan->id,
            'alamat' => $this->permintaan->alamat,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'tanggal' => $this->permintaan->tanggal,
            'jadwal' => $this->permintaan->jadwal,
        ];
    }
}
