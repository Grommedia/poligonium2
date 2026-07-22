<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('plg_course_categories_translations')) {
            Schema::create('plg_course_categories_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_course_categories_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();

                $table->primary(['lang_code', 'plg_course_categories_id'], 'plg_course_categories_translations_primary');
            });
        }

        if (! Schema::hasTable('plg_courses_translations')) {
            Schema::create('plg_courses_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_courses_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();

                $table->primary(['lang_code', 'plg_courses_id'], 'plg_courses_translations_primary');
            });
        }

        if (! Schema::hasTable('plg_course_chapters_translations')) {
            Schema::create('plg_course_chapters_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_course_chapters_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();

                $table->primary(['lang_code', 'plg_course_chapters_id'], 'plg_course_chapters_translations_primary');
            });
        }

        if (! Schema::hasTable('plg_course_lessons_translations')) {
            Schema::create('plg_course_lessons_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_course_lessons_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();

                $table->primary(['lang_code', 'plg_course_lessons_id'], 'plg_course_lessons_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_course_lessons_translations');
        Schema::dropIfExists('plg_course_chapters_translations');
        Schema::dropIfExists('plg_courses_translations');
        Schema::dropIfExists('plg_course_categories_translations');
    }
};
