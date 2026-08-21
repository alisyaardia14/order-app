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
            $table->string('order_code', 30)->unique();
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30);
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total_amount');
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])
                ->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
