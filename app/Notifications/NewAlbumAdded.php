<?php

namespace App\Notifications;

use App\Models\Album;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAlbumAdded extends Notification
{
    use Queueable;

    public function __construct(private readonly Album $album) {}

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
        return [
            'album_id' => $this->album->id,
            'album_title' => $this->album->title,
            'artist_name' => $this->album->artist->name,
            'message' => "New release: {$this->album->artist->name} - {$this->album->title}",
        ];
    }
}
