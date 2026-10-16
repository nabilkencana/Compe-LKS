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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDeleteCascade();
            $table->foreignId('job_vacancies_id')->constrained('job_vacancies')->onDeleteCascade();
            $table->foreignId('resume_id')->constrained()->onDeleteCascade();
            $table->string('cover_letter');
            $table->enum('is_current',['open','close'])->default('open');
            $table->date('applied_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};