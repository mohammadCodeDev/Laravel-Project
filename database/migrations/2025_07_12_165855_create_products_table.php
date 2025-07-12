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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // کلید اصلی، incremental 
            $table->string('name'); // نام محصول 
            $table->text('description')->nullable(); // توضیحات محصول 
            $table->decimal('price', 10, 2); // قیمت بر حسب کیلوگرم 
            $table->decimal('stock', 8, 2); // موجودی انبار 
            $table->string('image')->nullable(); // آدرس تصویر محصول 
            $table->timestamps(); // ایجاد ستون‌های created_at و updated_at 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
