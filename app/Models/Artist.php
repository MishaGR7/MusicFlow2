<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'bio',
    'country',
    'debut_date',
    'company',
    'artist_type',
    'members_count',
    'fandom_name',
    'official_site',
    'photo',
])]
class Artist extends Model
{
    protected function casts(): array
    {
        return [
            'debut_date' => 'date',
            'members_count' => 'integer',
        ];
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
