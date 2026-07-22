<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('plg_course_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('plg_course_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_courses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('plg_course_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('intro_video')->nullable();
            $table->string('difficulty', 80)->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->unsignedInteger('lesson_count')->default(0);
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free_preview')->default(false);
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_course_chapters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_course_lessons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('plg_course_chapters')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('video_path')->nullable();
            $table->text('video_embed')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->boolean('requires_access')->default(true);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_course_lesson_files', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('plg_courses')->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained('plg_course_lessons')->cascadeOnDelete();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->boolean('is_downloadable')->default(true);
            $table->boolean('requires_access')->default(true);
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_course_enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
            $table->string('source', 60)->default('manual');
            $table->string('status', 60)->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::create('plg_course_purchases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->string('status', 60)->default('pending');
            $table->string('payment_provider', 120)->nullable();
            $table->string('payment_reference', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_course_purchases');
        Schema::dropIfExists('plg_course_enrollments');
        Schema::dropIfExists('plg_course_lesson_files');
        Schema::dropIfExists('plg_course_lessons');
        Schema::dropIfExists('plg_course_chapters');
        Schema::dropIfExists('plg_courses');
        Schema::dropIfExists('plg_course_categories');
    }
};
