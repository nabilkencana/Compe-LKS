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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shipping_service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->string('sender_names');
            $table->string('sender_phones');
            $table->string('sender_addresses');
            $table->string('package_description');
            $table->integer('weight');
            $table->integer('shipping_fee')->nullable();
            $table->enum('status', ['pending','confirmed','assigned','picked_up','in_transit','out_for_delivery','failed_delivery','delivered','cancelled']);
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
