<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_course_lessons', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_course_lessons', 'material_type')) {
                $table->string('material_type', 60)->default('video')->after('content');
            }

            if (! Schema::hasColumn('plg_course_lessons', 'access_mode')) {
                $table->string('access_mode', 60)->default('paid')->after('duration_minutes');
            }

            if (! Schema::hasColumn('plg_course_lessons', 'opens_at')) {
                $table->dateTime('opens_at')->nullable()->after('access_mode');
            }

            if (! Schema::hasColumn('plg_course_lessons', 'drip_days')) {
                $table->unsignedInteger('drip_days')->nullable()->after('opens_at');
            }

            if (! Schema::hasColumn('plg_course_lessons', 'video_status')) {
                $table->string('video_status', 60)->default('missing')->after('video_embed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plg_course_lessons', function (Blueprint $table): void {
            foreach (['material_type', 'access_mode', 'opens_at', 'drip_days', 'video_status'] as $column) {
                if (Schema::hasColumn('plg_course_lessons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
