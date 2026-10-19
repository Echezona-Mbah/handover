<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('doctors', 'password_reset_token')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->string('password_reset_token')->nullable()->after('password');
            });
        }

        if (!Schema::hasColumn('doctors', 'password_reset_expires_at')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->timestamp('password_reset_expires_at')->nullable()->after('password_reset_token');
            });
        }

        if (!Schema::hasColumn('doctors', 'password_set')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->boolean('password_set')->default(false)->after('password_reset_expires_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'password_set')) {
                $table->dropColumn('password_set');
            }

            if (Schema::hasColumn('doctors', 'password_reset_expires_at')) {
                $table->dropColumn('password_reset_expires_at');
            }

            if (Schema::hasColumn('doctors', 'password_reset_token')) {
                $table->dropColumn('password_reset_token');
            }
        });
    }
};