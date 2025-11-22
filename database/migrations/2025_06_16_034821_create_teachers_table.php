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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            
            // البيانات الأساسية
            $table->string('name');
            $table->string('email')->unique();
            $table->string('qualification');
            $table->string('subject'); // مادة التدريس
            
            // معلومات الاتصال
            $table->string('phone');           // رقم الجوال
            $table->string('home_phone')->nullable(); // رقم المنزل
            
            // العنوان
            $table->text('address')->nullable();
            
            // معلومات إضافية
            $table->string('specialization')->nullable(); // التخصص
            $table->integer('experience_years')->nullable(); // سنوات الخبرة
            $table->decimal('salary', 8, 2)->nullable(); // الراتب
            
            // حالة المعلم
            $table->enum('status', ['active', 'inactive', 'suspended', 'retired'])
                  ->default('active');
            
            // التاريخ والوقت
            $table->timestamp('hire_date')->nullable(); // تاريخ التوظيف
            $table->text('notes')->nullable(); // ملاحظات إضافية
            
            $table->timestamps();
            
            // فهارس للأداء
            $table->index('email');
            $table->index('subject');
            $table->index('status');
            $table->index('qualification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};