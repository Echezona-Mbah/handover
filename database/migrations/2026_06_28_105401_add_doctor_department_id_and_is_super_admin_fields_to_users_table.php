<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'doctor_department_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('doctor_department_id')
                    ->nullable()
                    ->constrained('doctor_departments')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('users', 'is_super_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_super_admin')->default(false);
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'doctor_department_id')) {
                $table->dropConstrainedForeignId('doctor_department_id');
            }

            if (Schema::hasColumn('users', 'is_super_admin')) {
                $table->dropColumn('is_super_admin');
            }
        });
    }
};