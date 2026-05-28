<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['artist_id', 'title', 'release_date', 'status', 'cover'])]
class Album extends Model
{
    protected function casts(): array
    {
        return [
            // 'release_date' => 'date',
        ];
    }

    public function getReleaseYearAttribute(): ?string
    {
        return $this->release_date ? explode('-', $this->release_date)[0] : null;
    }

    public function getReleaseMonthAttribute(): ?string
    {
        $parts = $this->release_date ? explode('-', $this->release_date) : [];

        return count($parts) >= 2 ? $parts[1] : null;
    }

    public function getReleaseDayAttribute(): ?string
    {
        $parts = $this->release_date ? explode('-', $this->release_date) : [];

        return count($parts) >= 3 ? $parts[2] : null;
    }

    public function getFormattedReleaseDateAttribute(): ?string
    {
        if (! $this->release_date) {
            return null;
        }

        $parts = explode('-', $this->release_date);

        if (count($parts) === 3) {
            // Full date
            $date = Carbon::createFromFormat('Y-m-d', $this->release_date);

            return $date->format('d M Y');
        } elseif (count($parts) === 2) {
            // Year and month
            $date = Carbon::createFromFormat('Y-m', $this->release_date);

            return $date->format('M Y');
        } elseif (count($parts) === 1) {
            // Year only
            return $parts[0];
        }

        return $this->release_date; // Fallback
    }

    public function getReleaseLabelAttribute(): string
    {
        return $this->formatted_release_date
            ?? match ($this->status) {
                'soon' => 'Coming Soon',
                'announced' => 'Announced',
                default => 'TBA',
            };
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(AlbumTrack::class)->orderBy('position');
    }

    public function titleTrack(): HasOne
    {
        return $this->hasOne(AlbumTrack::class)->where('is_title_track', true);
    }
}
