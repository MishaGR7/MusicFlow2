<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['album_id', 'position', 'title', 'duration', 'is_title_track'])]
class AlbumTrack extends Model
{
    protected function casts(): array
    {
        return [
            'is_title_track' => 'boolean',
        ];
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
