<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'bio', 'country', 'photo'])]
class Artist extends Model
{
    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
