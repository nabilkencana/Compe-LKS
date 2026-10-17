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
             $table->foreignId('bill_id')->constrained()->cascadeOnDelete();
             $table->date('transaction_reference');
             $table->integer('amount');
             $table->enum('payment_method',['bank_transfer','retail','other']);
             $table->string('payment_proof');
             $table->enum('status',['pending','completed']);
             $table->date('paid')->isCurrent();
             $table->string('reviewed_by');
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
