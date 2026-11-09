<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'nursing_hierarchy_role')) {
                $table->string('nursing_hierarchy_role', 30)->nullable()->index()->after('nursing_admin_role');
            }
        });
        Schema::table('nurses', function (Blueprint $table) {
            if (! Schema::hasColumn('nurses', 'nursing_hierarchy_role')) {
                $column = $table->string('nursing_hierarchy_role', 30)->nullable();
                // Older installations have a `role` column but no nursing_role_id.
                if (Schema::hasColumn('nurses', 'nursing_role_id')) {
                    $column->after('nursing_role_id');
                }
                $table->index('nursing_hierarchy_role');
            }
        });
        Schema::table('wards', function (Blueprint $table) {
            if (! Schema::hasColumn('wards', 'unit_head_id')) {
                $table->foreignId('unit_head_id')->nullable()->constrained('users')->nullOnDelete()->after('hospital_id');
            }
        });
        Schema::table('nursing_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('nursing_reports', 'recipient_type')) {
                $table->string('recipient_type', 20)->nullable()->after('target_admin_role');
                $table->unsignedBigInteger('recipient_id')->nullable()->after('recipient_type');
                $table->string('recipient_role', 30)->nullable()->index()->after('recipient_id');
                $table->index(['recipient_type', 'recipient_id'], 'nursing_reports_recipient_index');
            }
        });

        Schema::create('nursing_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('nursing_report_id')->nullable()->constrained('nursing_reports')->nullOnDelete();
            $table->string('sender_type', 20); $table->unsignedBigInteger('sender_id');
            $table->string('recipient_type', 20); $table->unsignedBigInteger('recipient_id');
            $table->longText('message');
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->index(['recipient_type', 'recipient_id'], 'nursing_queries_recipient_index');
        });
        Schema::create('nursing_query_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursing_query_id')->constrained()->cascadeOnDelete();
            $table->string('responder_type', 20); $table->unsignedBigInteger('responder_id');
            $table->longText('message'); $table->timestamps();
        });
        Schema::create('nursing_escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursing_report_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('nursing_query_id')->nullable()->constrained()->nullOnDelete();
            $table->string('escalated_by_type', 20); $table->unsignedBigInteger('escalated_by_id');
            $table->longText('reason'); $table->timestamp('resolved_at')->nullable(); $table->timestamps();
        });
        Schema::create('nursing_audit_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->string('actor_type', 20)->nullable(); $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('action', 80); $table->nullableMorphs('auditable'); $table->json('metadata')->nullable(); $table->timestamps();
        });
    }
    public function down(): void
    {
        // Deliberately only removes structures introduced by this migration; it never touches existing nursing data.
        Schema::dropIfExists('nursing_audit_logs'); Schema::dropIfExists('nursing_escalations'); Schema::dropIfExists('nursing_query_responses'); Schema::dropIfExists('nursing_queries');
        Schema::table('nursing_reports', function (Blueprint $table) { if (Schema::hasColumn('nursing_reports', 'recipient_type')) { $table->dropIndex('nursing_reports_recipient_index'); $table->dropColumn(['recipient_type','recipient_id','recipient_role']); } });
        Schema::table('wards', function (Blueprint $table) { if (Schema::hasColumn('wards', 'unit_head_id')) $table->dropConstrainedForeignId('unit_head_id'); });
        Schema::table('nurses', function (Blueprint $table) { if (Schema::hasColumn('nurses', 'nursing_hierarchy_role')) $table->dropColumn('nursing_hierarchy_role'); });
        Schema::table('users', function (Blueprint $table) { if (Schema::hasColumn('users', 'nursing_hierarchy_role')) $table->dropColumn('nursing_hierarchy_role'); });
    }
};
