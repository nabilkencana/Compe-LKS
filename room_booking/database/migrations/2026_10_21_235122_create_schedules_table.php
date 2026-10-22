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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->constrained()->cascadeOnDelete();
            $table->string('room_id')->constrained()->cascadeOnDelete();
            $table->string('activity_id')->constrained()->cascadeOnDelete();
            $table->date('start_at');
            $table->date('end_at');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
