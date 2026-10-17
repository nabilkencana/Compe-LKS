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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
             $table->foreignId('electricity_account_id')->constrained()->cascadeOnDelete();
                         $table->date('billing_period');
                         $table->integer('usage_kwh');
                         $table->integer('price_per_kwh');
                         $table->integer('total_amount');
                         $table->enum('status',['paid','unpaid','cancelled']);
                         $table->date('due_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
