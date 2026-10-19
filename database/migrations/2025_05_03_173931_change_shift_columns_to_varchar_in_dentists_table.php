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
            Schema::table('dentists', function (Blueprint $table) {
                $table->string('sun_start', 10)->nullable()->change();
                $table->string('sun_end', 10)->nullable()->change();
                $table->string('mon_start', 10)->nullable()->change();
                $table->string('mon_end', 10)->nullable()->change();
                $table->string('tue_start', 10)->nullable()->change();
                $table->string('tue_end', 10)->nullable()->change();
                $table->string('wed_start', 10)->nullable()->change();
                $table->string('wed_end', 10)->nullable()->change();
                $table->string('thu_start', 10)->nullable()->change();
                $table->string('thu_end', 10)->nullable()->change();
                $table->string('fri_start', 10)->nullable()->change();
                $table->string('fri_end', 10)->nullable()->change();
                $table->string('sat_start', 10)->nullable()->change();
                $table->string('sat_end', 10)->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dentists', function (Blueprint $table) {
            Schema::table('dentists', function (Blueprint $table) {
                $table->time('sun_start')->nullable()->change();
                $table->time('sun_end')->nullable()->change();
                $table->time('mon_start')->nullable()->change();
                $table->time('mon_end')->nullable()->change();
                $table->time('tue_start')->nullable()->change();
                $table->time('tue_end')->nullable()->change();
                $table->time('wed_start')->nullable()->change();
                $table->time('wed_end')->nullable()->change();
                $table->time('thu_start')->nullable()->change();
                $table->time('thu_end')->nullable()->change();
                $table->time('fri_start')->nullable()->change();
                $table->time('fri_end')->nullable()->change();
                $table->time('sat_start')->nullable()->change();
                $table->time('sat_end')->nullable()->change();
            });
        });
    }
};
