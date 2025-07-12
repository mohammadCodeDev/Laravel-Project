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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // کلید اصلی، incremental 
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // کلید خارجی به جدول orders 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // کلید خارجی به جدول products 
            $table->integer('quantity'); // تعداد ثبت شده 
            $table->decimal('price', 10, 2); // قیمت محصول در زمان ثبت سفارش 
            $table->timestamps(); // ایجاد ستون‌های created_at و updated_at 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
