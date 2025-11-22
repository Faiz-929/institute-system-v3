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
        // تأكد من وجود الجداول قبل إضافة foreign keys
        if (Schema::hasTable('assets') && Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                // التحقق من وجود عمود asset_id قبل إضافة foreign key
                if (Schema::hasColumn('assignments', 'asset_id')) {
                    $table->foreign('asset_id')
                          ->references('id')
                          ->on('assets')
                          ->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('consumables') && Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                if (Schema::hasColumn('assignments', 'consumable_id')) {
                    $table->foreign('consumable_id')
                          ->references('id')
                          ->on('consumables')
                          ->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropForeign(['asset_id']);
                $table->dropForeign(['consumable_id']);
            });
        }
    }
};