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
        Schema::create('aspect_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspect_id')->constrained('aspects')->cascadeOnDelete();
            $table->int('version_number');
            $table->string('name');
            $table->effectiveFrom();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspect_versions');
    }
};
