<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('plg_course_opening_reminders')) {
            return;
        }

        Schema::create('plg_course_opening_reminders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained('plg_courses')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email')->nullable();
            $table->timestamp('remind_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'user_id']);
            $table->index(['course_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_course_opening_reminders');
    }
};
