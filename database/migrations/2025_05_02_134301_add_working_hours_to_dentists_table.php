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
        Schema::table('dentists', function (Blueprint $table) {
            $table->time('sun_start')->nullable();
            $table->time('sun_end')->nullable();
            $table->time('mon_start')->nullable();
            $table->time('mon_end')->nullable();
            $table->time('tue_start')->nullable();
            $table->time('tue_end')->nullable();
            $table->time('wed_start')->nullable();
            $table->time('wed_end')->nullable();
            $table->time('thu_start')->nullable();
            $table->time('thu_end')->nullable();
            $table->time('fri_start')->nullable();
            $table->time('fri_end')->nullable();
            $table->time('sat_start')->nullable();
            $table->time('sat_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dentists', function (Blueprint $table) {
            $table->dropColumn([
                'sun_start', 'sun_end',
                'mon_start', 'mon_end',
                'tue_start', 'tue_end',
                'wed_start', 'wed_end',
                'thu_start', 'thu_end',
                'fri_start', 'fri_end',
                'sat_start', 'sat_end',
            ]);
        });
    }
};
