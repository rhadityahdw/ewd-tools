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
            $table->foreignId('aspect_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('effective_from');
            $table->timestamps();

            $table->unique(['aspect_id', 'version_number']);
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
