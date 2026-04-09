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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_private')->default(false);
            $table->boolean('show_activity')->default(true);
            $table->boolean('read_receipts')->default(true);
            $table->boolean('restrict_mentions')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_private', 'show_activity', 'read_receipts', 'restrict_mentions']);
        });
    }
};
