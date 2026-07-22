<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('plg_courses', 'image')) {
            DB::statement('ALTER TABLE plg_courses MODIFY image TEXT NULL');
        }

        if (Schema::hasColumn('plg_courses', 'intro_video')) {
            DB::statement('ALTER TABLE plg_courses MODIFY intro_video TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('plg_courses', 'image')) {
            DB::statement('ALTER TABLE plg_courses MODIFY image VARCHAR(191) NULL');
        }

        if (Schema::hasColumn('plg_courses', 'intro_video')) {
            DB::statement('ALTER TABLE plg_courses MODIFY intro_video VARCHAR(191) NULL');
        }
    }
};
