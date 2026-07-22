<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('plg_campaign_support_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->foreignId('reward_id')->nullable()->constrained('plg_campaign_rewards')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->text('message')->nullable();
            $table->string('status', 80)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_campaign_support_requests');
    }
};
