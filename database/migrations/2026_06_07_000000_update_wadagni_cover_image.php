<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('posts')
            ->where('cover_image', 'covers/wadagni-benin.jpg')
            ->update(['cover_image' => 'covers/wadagni-portrait-2026.jpg']);
    }

    public function down(): void
    {
        DB::table('posts')
            ->where('cover_image', 'covers/wadagni-portrait-2026.jpg')
            ->update(['cover_image' => 'covers/wadagni-benin.jpg']);
    }
};
