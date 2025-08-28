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
        Schema::create('agency_expertise', function (Blueprint $t) {
            $t->foreignUuid('agency_id')->constrained('agencies')->cascadeOnDelete();
            $t->foreignId('expertise_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('weight')->default(50); // 0..100 indicates strength
            
            // Composite PK ensures uniqueness
            $t->primary(['agency_id', 'expertise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_expertise');
    }
};
