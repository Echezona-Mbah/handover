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
    Schema::table('doctors', function (Blueprint $table) {

        $table->foreign('doctor_sub_unit_id')
              ->references('id')
              ->on('doctor_sub_units')
              ->nullOnDelete();

    });
}

public function down(): void
{
    Schema::table('doctors', function (Blueprint $table) {

        $table->dropForeign(['doctor_sub_unit_id']);

    });
}
};
