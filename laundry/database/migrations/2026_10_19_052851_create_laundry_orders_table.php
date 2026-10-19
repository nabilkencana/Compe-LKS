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
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->foreignId('laundry_service_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity',10,2);
            $table->decimal('unit_price',12,2);
            $table->decimal('service_subtotal',12,2);
            $table->boolean('requires_delivery')->default(true);
            $table->string('delivery_address');
            $table->decimal('delivery_fee',12,2);
            $table->decimal('final_total',12,2);
            $table->enum('status' , ['pending','confirmed','washing','drying','ironing','ready_for_pickup','completed','cancelled']);
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundry_orders');
    }
};
