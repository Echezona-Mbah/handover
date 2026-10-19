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
        Schema::table('shift_schedules', function (Blueprint $table) {
            $table->string('sun_start')->nullable();
            $table->string('sun_end')->nullable();
            $table->string('mon_start')->nullable();
            $table->string('mon_end')->nullable();
            $table->string('tue_start')->nullable();
            $table->string('tue_end')->nullable();
            $table->string('wed_start')->nullable();
            $table->string('wed_end')->nullable();
            $table->string('thu_start')->nullable();
            $table->string('thu_end')->nullable();
            $table->string('fri_start')->nullable();
            $table->string('fri_end')->nullable();
            $table->string('sat_start')->nullable();
            $table->string('sat_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_schedules', function (Blueprint $table) {
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
