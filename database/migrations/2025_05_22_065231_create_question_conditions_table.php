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
        Schema::create('question_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->enum('entity', ['borrower', 'borrower_detail', 'question']);
            $table->string('field');
            $table->enum('operator', ['=', '!=', '>', '<', '>=', '<=', 'in', 'not in', 'contains', 'not contains']);
            $table->text('value');
            $table->enum('logic_operator', ['AND', 'OR'])->default('AND');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_conditions');
    }
};
