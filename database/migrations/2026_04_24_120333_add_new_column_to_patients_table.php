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
        Schema::table('patients', function (Blueprint $table) {
            $table->unsignedBigInteger('pending_transfer_unit_id')->nullable()->after('doctor_sub_unit_id');
            $table->unsignedBigInteger('pending_transfer_sub_unit_id')->nullable()->after('pending_transfer_unit_id');
            $table->unsignedBigInteger('transfer_from_unit_id')->nullable()->after('pending_transfer_sub_unit_id');
            $table->unsignedBigInteger('transfer_from_sub_unit_id')->nullable()->after('transfer_from_unit_id');
            $table->string('transfer_status')->nullable()->after('transfer_from_sub_unit_id'); // pending, accepted, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
