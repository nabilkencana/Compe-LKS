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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('complaint_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('evidence_image')->nullable();
            $table->enum('status', ['submitted','accepted','rejected']);
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
