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
        Schema::create('report_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->enum('final_classification', enum_values(Classification::class));
            $table->int('indicative_collectibility');
            $table->boolean('override');
            $table->text('override_reason');
            $table->text('business_notes');
            $table->text('reviewer_notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_summaries');
    }
};
