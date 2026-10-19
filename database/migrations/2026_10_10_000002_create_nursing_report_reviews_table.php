<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
          if (Schema::hasTable('nursing_report_reviews')) {
            return;
        }
        Schema::create('nursing_report_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursing_report_id')->constrained('nursing_reports')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('action', ['comment', 'request_clarification', 'reviewed', 'approved'])->default('comment');
            $table->longText('comment');
            $table->json('snapshot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_report_reviews');
    }
};

