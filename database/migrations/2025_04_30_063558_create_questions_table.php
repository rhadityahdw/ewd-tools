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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspect_id')->constrained('aspects')->cascadeOnDelete();
            $table->text('question_text');
            $table->decimal('weight', 5, 2)->default(0.00);
            $table->decimal('max_score', 5, 2)->default(1.00);
            $table->decimal('min_score', 5, 2)->default(0.00);
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
