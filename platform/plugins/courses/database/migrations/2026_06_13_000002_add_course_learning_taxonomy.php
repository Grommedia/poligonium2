<?php

use Botble\Courses\Support\CourseOptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_courses', 'software')) {
                $table->json('software')->nullable()->after('difficulty');
            }

            if (! Schema::hasColumn('plg_courses', 'skills')) {
                $table->json('skills')->nullable()->after('software');
            }
        });

        $now = now();

        foreach (CourseOptions::defaultCategories() as $category) {
            DB::table('plg_course_categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'parent_id' => null,
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'order' => $category['order'],
                    'status' => 'published',
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $testCategoryIds = DB::table('plg_course_categories')
            ->whereIn('slug', ['charivnyi-pavuk', 'charivnyy-pavuk', 'magic-spider'])
            ->orWhere('name', 'like', '%павук%')
            ->pluck('id');

        if ($testCategoryIds->isNotEmpty()) {
            DB::table('plg_courses')
                ->whereIn('category_id', $testCategoryIds)
                ->update(['category_id' => null]);

            DB::table('plg_course_categories')
                ->whereIn('id', $testCategoryIds)
                ->delete();
        }
    }

    public function down(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            if (Schema::hasColumn('plg_courses', 'skills')) {
                $table->dropColumn('skills');
            }

            if (Schema::hasColumn('plg_courses', 'software')) {
                $table->dropColumn('software');
            }
        });
    }
};
