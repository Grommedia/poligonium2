<?php

use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('plg_vfx_showreel_items', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('poster')->nullable();
            $table->string('preview_video')->nullable();
            $table->string('tools')->nullable();
            $table->string('year')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('status', 60)->default(BaseStatusEnum::PUBLISHED);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_vfx_showreel_items');
    }
};
