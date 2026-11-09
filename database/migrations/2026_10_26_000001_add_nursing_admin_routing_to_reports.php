<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'nursing_admin_role')) {
                $table->string('nursing_admin_role', 50)->nullable()->after('is_super_admin');
            }
        });

        Schema::table('nursing_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('nursing_reports', 'assigned_admin_id')) {
                $table->foreignId('assigned_admin_id')
                    ->nullable()
                    ->after('nurse_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // Reports can now be declined by the nursing administrator chosen by the nurse.
            $table->string('status', 40)->default('submitted')->change();
        });

        Schema::table('nursing_report_reviews', function (Blueprint $table) {
            $table->string('action', 50)->default('comment')->change();
        });
    }

    public function down(): void
    {
        Schema::table('nursing_reports', function (Blueprint $table) {
            if (Schema::hasColumn('nursing_reports', 'assigned_admin_id')) {
                $table->dropConstrainedForeignId('assigned_admin_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nursing_admin_role')) {
                $table->dropColumn('nursing_admin_role');
            }
        });
    }
};
