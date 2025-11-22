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
        if (!Schema::hasTable('assignments')) {
            Schema::create('assignments', function (Blueprint $table) {
                $table->id();
                
                // العلاقة مع الورشة
                $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();
                
                // استخدام unsignedBigInteger بدلاً من foreignId المباشر
                // سنضيف Foreign Key بعد إنشاء الجداول
                $table->unsignedBigInteger('asset_id')->nullable();
                $table->unsignedBigInteger('consumable_id')->nullable();
                
                // معلومات المهمة
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('assigned_to');
                $table->date('assigned_date')->nullable();
                $table->date('return_date')->nullable();
                $table->enum('status', ['pending', 'assigned', 'returned', 'overdue'])->default('pending');
                $table->text('note')->nullable();
                $table->timestamps();
                
                // فهارس للأداء
                $table->index('asset_id');
                $table->index('consumable_id');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
