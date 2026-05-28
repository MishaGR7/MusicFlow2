<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('album_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->string('title');
            $table->string('duration', 12)->nullable();
            $table->boolean('is_title_track')->default(false);
            $table->timestamps();

            $table->unique(['album_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('album_tracks');
    }
};
