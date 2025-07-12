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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // کلید اصلی، incremental 
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // کلید خارجی به جدول users 
            $table->decimal('total_price', 12, 2); // مجموع قیمت سفارش 
            // وضعیت سفارش: در انتظار، تایید شده، لغو شده 
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'canceled'])->default('pending');
            $table->timestamps(); // ایجاد ستون‌های created_at و updated_at 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
