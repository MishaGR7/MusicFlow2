<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('spotify_url')->nullable()->after('official_site');
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->string('spotify_url')->nullable()->after('cover');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('spotify_url');
        });

        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn('spotify_url');
        });
    }
};
