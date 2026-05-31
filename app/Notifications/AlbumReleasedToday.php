<?php

namespace App\Notifications;

use App\Models\Album;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AlbumReleasedToday extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Album $album,
        private readonly ?string $referenceDate = null,
    ) {}

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
        $isReleaseDay = $this->album->release_date === ($this->referenceDate ?: now()->toDateString());
        $message = $isReleaseDay
            ? "Released today: {$this->album->artist->name} - {$this->album->title}"
            : "Release available: {$this->album->artist->name} - {$this->album->title}";

        return [
            'kind' => 'release_day',
            'album_id' => $this->album->id,
            'album_title' => $this->album->title,
            'artist_name' => $this->album->artist->name,
            'release_date' => $this->album->release_date,
            'message' => $message,
            'action_url' => route('albums.show', $this->album),
            'action_label' => 'Open release',
        ];
    }
}
