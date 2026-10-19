<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

    if (Schema::hasTable('nursing_roster_assignments')) {
            return;
        }

        Schema::create('nursing_roster_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursing_roster_id')->constrained('nursing_rosters')->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('nurses')->cascadeOnDelete();
            $table->date('duty_date');
            $table->string('shift_type');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_roster_assignments');
    }
};

