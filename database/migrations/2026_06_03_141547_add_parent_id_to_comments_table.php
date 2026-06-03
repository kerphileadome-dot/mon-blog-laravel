<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('post_id')->constrained('comments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->after('parent_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['parent_id', 'user_id']);
        });
    }
};
