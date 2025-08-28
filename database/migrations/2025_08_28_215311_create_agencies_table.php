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
        Schema::create('agencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('country_code', 2)->index();
            $table->string('city')->nullable();
            
            // Hourly rates (USD for simplicity)
            $table->unsignedInteger('hourly_rate_min')->nullable();
            $table->unsignedInteger('hourly_rate_max')->nullable();
            
            $table->boolean('is_verified')->default(false);
            
            // Denormalised aggregates (updated via observers/jobs)
            $table->unsignedSmallInteger('review_avg_times10')->default(0); // e.g., 45 == 4.5
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedSmallInteger('response_time_minutes')->default(1440); // SLA median
            $table->unsignedSmallInteger('case_studies_count')->default(0);
            $table->unsignedSmallInteger('awards_count')->default(0);
            $table->unsignedSmallInteger('latest_award_year')->nullable();
            
            $table->timestamps();
            
            // Helpful composite indexes for common queries
            $table->index(['country_code', 'city']);
            $table->index(['is_verified', 'country_code']);
            $table->index(['review_avg_times10', 'review_count']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
