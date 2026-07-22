<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_campaign_rewards', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_campaign_rewards', 'manual_backers')) {
                $table->unsignedInteger('manual_backers')->default(0)->after('quantity_limit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plg_campaign_rewards', function (Blueprint $table): void {
            if (Schema::hasColumn('plg_campaign_rewards', 'manual_backers')) {
                $table->dropColumn('manual_backers');
            }
        });
    }
};
