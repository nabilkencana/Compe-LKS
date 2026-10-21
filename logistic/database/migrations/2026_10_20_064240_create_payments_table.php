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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verifed_by')->constrained('users')->cascadeOnDelete();
            $table->integer('amount');
            $table->string('payment_method');
            $table->string('payment_proof')->nullable();
            $table->enum('status',['pending','completed','rejected']);
            $table->date('verified_at')->isCurrent();
            $table->date('paid_at')->isCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
