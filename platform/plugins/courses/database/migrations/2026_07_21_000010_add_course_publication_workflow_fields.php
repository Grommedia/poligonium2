<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_courses', 'publication_state')) {
                $table->string('publication_state', 60)->default('draft')->after('gradual_access_enabled');
            }

            if (! Schema::hasColumn('plg_courses', 'publish_scheduled_at')) {
                $table->timestamp('publish_scheduled_at')->nullable()->after('publication_state');
            }

            if (! Schema::hasColumn('plg_courses', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('publish_scheduled_at');
            }

            if (! Schema::hasColumn('plg_courses', 'published_snapshot')) {
                $table->longText('published_snapshot')->nullable()->after('published_at');
            }

            if (! Schema::hasColumn('plg_courses', 'has_unpublished_changes')) {
                $table->boolean('has_unpublished_changes')->default(false)->after('published_snapshot');
            }
        });

        DB::table('plg_courses')
            ->where('status', 'published')
            ->where(function ($query): void {
                $query->whereNull('publication_state')->orWhere('publication_state', 'draft');
            })
            ->update([
                'publication_state' => 'published',
                'published_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            foreach ([
                'publication_state',
                'publish_scheduled_at',
                'published_at',
                'published_snapshot',
                'has_unpublished_changes',
            ] as $column) {
                if (Schema::hasColumn('plg_courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
