<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nursing_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('nursing_reports', 'target_admin_role')) {
                $table->string('target_admin_role', 50)->nullable()->after('assigned_admin_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('nursing_reports', function (Blueprint $table) {
            if (Schema::hasColumn('nursing_reports', 'target_admin_role')) {
                $table->dropIndex(['target_admin_role']);
                $table->dropColumn('target_admin_role');
            }
        });
    }
};
