<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\AlbumTrack;
use App\Models\Artist;
use Illuminate\Notifications\DatabaseNotification;

abstract class Controller
{
    protected function artistData(Artist $artist, bool $withAlbums = false): array
    {
        $data = [
            'id' => $artist->id,
            'name' => $artist->name,
            'bio' => $artist->bio,
            'country' => $artist->country,
            'debut_date' => $artist->debut_date?->format('Y-m-d'),
            'debut_date_label' => $artist->debut_date?->format('d M Y'),
            'company' => $artist->company,
            'artist_type' => $artist->artist_type,
            'artist_type_label' => ucfirst($artist->artist_type ?? 'group'),
            'members_count' => $artist->members_count,
            'fandom_name' => $artist->fandom_name,
            'official_site' => $artist->official_site,
            'spotify_url' => $artist->spotify_url,
            'instagram_url' => $artist->instagram_url,
            'photo_url' => $artist->photo ? asset('storage/'.$artist->photo) : 'https://placehold.co/800x800/020617/a78bfa?text=Artist',
            'albums_count' => $artist->albums_count ?? $artist->albums()->count(),
            'followers_count' => $artist->followers_count ?? ($artist->relationLoaded('followers') ? $artist->followers->count() : $artist->followers()->count()),
        ];

        if ($withAlbums) {
            $data['albums'] = $artist->albums->map(fn (Album $album) => $this->albumData($album))->values();
        }

        return $data;
    }

    protected function albumData(Album $album, bool $withTracks = false): array
    {
        $data = [
            'id' => $album->id,
            'artist_id' => $album->artist_id,
            'title' => $album->title,
            'release_date' => $album->release_date,
            'release_year' => $album->release_year,
            'release_month' => $album->release_month,
            'release_day' => $album->release_day,
            'release_label' => $album->release_label,
            'status' => $album->status,
            'cover_url' => $album->cover ? asset('storage/'.$album->cover) : 'https://placehold.co/800x800/0f172a/a78bfa?text=MusicFlow',
            'spotify_url' => $album->spotify_url,
            'tracks_count' => $album->tracks_count ?? ($album->relationLoaded('tracks') ? $album->tracks->count() : $album->tracks()->count()),
            'title_track' => $album->relationLoaded('titleTrack') && $album->titleTrack ? $this->trackData($album->titleTrack) : null,
            'artist' => $album->relationLoaded('artist') && $album->artist ? [
                'id' => $album->artist->id,
                'name' => $album->artist->name,
                'country' => $album->artist->country,
            ] : null,
        ];

        if ($withTracks) {
            $data['tracks'] = $album->tracks->map(fn (AlbumTrack $track) => $this->trackData($track))->values();
        }

        return $data;
    }

    protected function trackData(AlbumTrack $track): array
    {
        return [
            'id' => $track->id,
            'position' => $track->position,
            'title' => $track->title,
            'duration' => $track->duration,
            'is_title_track' => $track->is_title_track,
        ];
    }

    protected function paginationData($paginator, callable $mapper): array
    {
        return [
            'data' => $paginator->getCollection()->map($mapper)->values(),
            'links' => $paginator->linkCollection()->toArray(),
        ];
    }

    protected function notificationData(DatabaseNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'message' => $notification->data['message'] ?? 'New release update.',
            'action_url' => $notification->data['action_url'] ?? null,
            'action_label' => $notification->data['action_label'] ?? 'Open',
            'read_at' => $notification->read_at?->toISOString(),
            'created_at' => $notification->created_at->diffForHumans(),
        ];
    }
}
