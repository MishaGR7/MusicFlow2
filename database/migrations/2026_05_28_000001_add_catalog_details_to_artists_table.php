<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->date('debut_date')->nullable()->after('country');
            $table->string('company')->nullable()->after('debut_date');
            $table->string('artist_type')->default('group')->after('company');
            $table->unsignedSmallInteger('members_count')->nullable()->after('artist_type');
            $table->string('fandom_name')->nullable()->after('members_count');
            $table->string('official_site')->nullable()->after('fandom_name');
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn([
                'debut_date',
                'company',
                'artist_type',
                'members_count',
                'fandom_name',
                'official_site',
            ]);
        });
    }
};
