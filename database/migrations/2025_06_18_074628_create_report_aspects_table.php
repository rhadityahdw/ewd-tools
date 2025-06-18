<?php

use App\Enums\Classification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function App\Helpers\enum_values;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_aspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('aspect_version_id')->constrained('aspect_versions')->cascadeOnDelete();
            $table->decimal('total_score', 5, 2);
            $table->enum('classification', enum_values(Classification::class));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_aspects');
    }
};
