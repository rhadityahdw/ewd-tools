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
        Schema::create('borrower_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('borrowers')->cascadeOnDelete();
            $table->string('facility_name');
            $table->decimal('limit');
            $table->decimal('outstanding');
            $table->decimal('interest_rate');
            $table->decimal('principal_arrears');
            $table->decimal('interest_arrears');
            $table->integer('pdo_days');
            $table->date('maturity_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrower_facilities');
    }
};
