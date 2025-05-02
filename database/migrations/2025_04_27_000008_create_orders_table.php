<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 100)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('shipping_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->enum('status', ['placed', 'processing', 'shipping', 'delivered'])->nullable();
            $table->enum('payment_status', ['paid', 'not paid'])->nullable();
            $table->enum('payment_type', ['netbanking', 'upi', 'cod'])->nullable();
            $table->string('payment_transaction_id', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
