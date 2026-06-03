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
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('phone');
            $table->string('address');
            $table->text('note')->nullable();
            $table->decimal('subtotal', 10, 0)->default(0);
            $table->decimal('shipping_fee', 10, 0)->default(0);
            $table->decimal('total', 10, 0)->default(0);
            $table->string('payment_method')->default('cod');
            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 'delivering', 'completed', 'cancelled',
            ])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
