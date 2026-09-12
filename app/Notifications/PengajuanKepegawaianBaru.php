<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanKepegawaianBaru extends Notification
{
    use Queueable;

    protected $pengajuan;

    /**
     * Create a new notification instance.
     */
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $namaPegawai = $this->pengajuan->pegawai->user->name ?? 'Pegawai';
        
        return [
            'judul' => 'Perlu Review: Pengajuan Perubahan Data',
            'message' => $namaPegawai . ' mengajukan perubahan data kepegawaian. Menunggu validasi.',
            'url' => route('admin.validasi-kepegawaian.index')
        ];
    }
}
