<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_courses', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_courses', 'sale_status')) {
                $table->string('sale_status', 60)->default('standard')->after('price');
            }

            if (! Schema::hasColumn('plg_courses', 'early_access_price')) {
                $table->decimal('early_access_price', 12, 2)->nullable()->after('sale_status');
            }

            if (! Schema::hasColumn('plg_courses', 'early_access_starts_at')) {
                $table->timestamp('early_access_starts_at')->nullable()->after('early_access_price');
            }

            if (! Schema::hasColumn('plg_courses', 'early_access_ends_at')) {
                $table->timestamp('early_access_ends_at')->nullable()->after('early_access_starts_at');
            }

            if (! Schema::hasColumn('plg_courses', 'released_at')) {
                $table->timestamp('released_at')->nullable()->after('early_access_ends_at');
            }

            if (! Schema::hasColumn('plg_courses', 'early_access_slots')) {
                $table->unsignedInteger('early_access_slots')->nullable()->after('released_at');
            }

            if (! Schema::hasColumn('plg_courses', 'early_access_sold')) {
                $table->unsignedInteger('early_access_sold')->default(0)->after('early_access_slots');
            }
        });

        Schema::table('plg_course_purchases', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_course_purchases', 'purchase_type')) {
                $table->string('purchase_type', 60)->default('full')->after('course_id');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'full_price')) {
                $table->decimal('full_price', 12, 2)->nullable()->after('amount');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('full_price');
            }
        });

        if (! Schema::hasTable('plg_student_profiles')) {
            Schema::create('plg_student_profiles', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('display_name')->nullable();
                $table->string('avatar')->nullable();
                $table->text('bio')->nullable();
                $table->string('rank_slug', 60)->default('newcomer');
                $table->unsignedInteger('xp')->default(0);
                $table->boolean('public_gallery_enabled')->default(true);
                $table->timestamps();

                $table->unique('user_id');
            });
        }

        if (! Schema::hasTable('plg_course_progress')) {
            Schema::create('plg_course_progress', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
                $table->foreignId('lesson_id')->nullable()->constrained('plg_course_lessons')->cascadeOnDelete();
                $table->string('status', 60)->default('not_started');
                $table->unsignedInteger('progress_seconds')->default(0);
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'lesson_id']);
                $table->index(['user_id', 'course_id']);
            });
        }

        if (! Schema::hasTable('plg_school_gallery_projects')) {
            Schema::create('plg_school_gallery_projects', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('course_id')->nullable()->constrained('plg_courses')->nullOnDelete();
                $table->foreignId('lesson_id')->nullable()->constrained('plg_course_lessons')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('video')->nullable();
                $table->json('tools')->nullable();
                $table->string('status', 60)->default('draft');
                $table->boolean('is_featured')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'is_featured']);
                $table->index(['user_id', 'course_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_school_gallery_projects');
        Schema::dropIfExists('plg_course_progress');
        Schema::dropIfExists('plg_student_profiles');

        Schema::table('plg_course_purchases', function (Blueprint $table): void {
            foreach (['purchase_type', 'full_price', 'discount_amount'] as $column) {
                if (Schema::hasColumn('plg_course_purchases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('plg_courses', function (Blueprint $table): void {
            foreach ([
                'sale_status',
                'early_access_price',
                'early_access_starts_at',
                'early_access_ends_at',
                'released_at',
                'early_access_slots',
                'early_access_sold',
            ] as $column) {
                if (Schema::hasColumn('plg_courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
