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
        Schema::create('delivery_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_id')->constrained('users')->cascadeOnDelete()->nullable();
            $table->string('recipe_name')->nullable();
            $table->string('proof_file')->nullable();
            $table->string('delivery_notes')->nullable();
            $table->enum('delivery_result', ['delivered','failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_proofs');
    }
};
