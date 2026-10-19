<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wards', function (Blueprint $table) {
            $table->string('category')->default('Ward')->after('name');
            $table->string('linen_color')->nullable()->after('description');
        });

        Schema::table('nurses', function (Blueprint $table) {
            $table->string('qualification')->nullable()->after('license_number');
        });
    }

    public function down(): void
    {
        Schema::table('nurses', function (Blueprint $table) {
            $table->dropColumn('qualification');
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->dropColumn(['category', 'linen_color']);
        });
    }
};
