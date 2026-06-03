<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('published');
            $table->index('category');
            $table->index('views');
            $table->index('created_at');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('approved');
            $table->index('created_at');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['published']);
            $table->dropIndex(['category']);
            $table->dropIndex(['views']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['approved']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
        });
    }
};
