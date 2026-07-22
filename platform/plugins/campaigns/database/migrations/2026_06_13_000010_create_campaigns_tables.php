<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('plg_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('teaser_url')->nullable();
            $table->decimal('target_amount', 14, 2)->default(0);
            $table->decimal('manual_amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->string('production_stage', 80)->default('concept');
            $table->string('campaign_state', 80)->default('active');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_campaign_rewards', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->unsignedInteger('quantity_limit')->nullable();
            $table->string('estimated_delivery')->nullable();
            $table->text('includes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_campaign_contributions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->foreignId('reward_id')->nullable()->constrained('plg_campaign_rewards')->nullOnDelete();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('UAH');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('contribution_status', 80)->default('confirmed');
            $table->boolean('is_public')->default(true);
            $table->text('message')->nullable();
            $table->timestamp('contributed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('plg_campaign_updates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_campaign_team_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('url')->nullable();
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('plg_campaign_faqs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')->constrained('plg_campaigns')->cascadeOnDelete();
            $table->string('question');
            $table->text('answer')->nullable();
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plg_campaign_faqs');
        Schema::dropIfExists('plg_campaign_team_members');
        Schema::dropIfExists('plg_campaign_updates');
        Schema::dropIfExists('plg_campaign_contributions');
        Schema::dropIfExists('plg_campaign_rewards');
        Schema::dropIfExists('plg_campaigns');
    }
};
