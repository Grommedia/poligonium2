<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('plg_vfx_showreel_items_translations')) {
            Schema::create('plg_vfx_showreel_items_translations', function (Blueprint $table): void {
                $table->string('lang_code', 20);
                $table->foreignId('plg_vfx_showreel_items_id');
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->text('description')->nullable();

                $table->primary(
                    ['lang_code', 'plg_vfx_showreel_items_id'],
                    'plg_vfx_showreel_items_trans_primary'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_vfx_showreel_items_translations');
    }
};
