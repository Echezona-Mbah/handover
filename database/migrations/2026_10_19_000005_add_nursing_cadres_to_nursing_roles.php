<?php

use App\Models\NursingRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('nursing_roles')->insertOrIgnore(
            array_map(
                fn (string $name) => [
                    'name' => $name,
                    'description' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                NursingRole::CADRES,
            )
        );
    }

    public function down(): void
    {
        // Preserve cadre rows and any nurse assignments when rolling back.
    }
};
