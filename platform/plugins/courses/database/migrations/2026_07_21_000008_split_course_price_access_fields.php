<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_courses', 'visibility_mode')) {
                $table->string('visibility_mode', 60)->default('catalog')->after('lesson_count');
            }

            if (! Schema::hasColumn('plg_courses', 'price_type')) {
                $table->string('price_type', 60)->default('paid')->after('visibility_mode');
            }

            if (! Schema::hasColumn('plg_courses', 'sales_mode')) {
                $table->string('sales_mode', 60)->default('immediate')->after('sale_status');
            }

            if (! Schema::hasColumn('plg_courses', 'sales_starts_at')) {
                $table->timestamp('sales_starts_at')->nullable()->after('sales_mode');
            }

            if (! Schema::hasColumn('plg_courses', 'course_access_mode')) {
                $table->string('course_access_mode', 60)->default('immediate')->after('released_at');
            }

            if (! Schema::hasColumn('plg_courses', 'timezone')) {
                $table->string('timezone', 80)->default('Europe/Kyiv')->after('course_access_mode');
            }

            if (! Schema::hasColumn('plg_courses', 'show_release_date_on_card')) {
                $table->boolean('show_release_date_on_card')->default(true)->after('timezone');
            }

            if (! Schema::hasColumn('plg_courses', 'gradual_access_enabled')) {
                $table->boolean('gradual_access_enabled')->default(false)->after('show_release_date_on_card');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            foreach ([
                'visibility_mode',
                'price_type',
                'sales_mode',
                'sales_starts_at',
                'course_access_mode',
                'timezone',
                'show_release_date_on_card',
                'gradual_access_enabled',
            ] as $column) {
                if (Schema::hasColumn('plg_courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
