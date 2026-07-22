<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('announcements')) {
            return;
        }

        Schema::table('announcements', function (Blueprint $table): void {
            if (! Schema::hasIndex('announcements', 'announcements_public_lookup_index')) {
                $table->index(['is_active', 'start_date', 'end_date'], 'announcements_public_lookup_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('announcements')) {
            return;
        }

        Schema::table('announcements', function (Blueprint $table): void {
            if (Schema::hasIndex('announcements', 'announcements_public_lookup_index')) {
                $table->dropIndex('announcements_public_lookup_index');
            }
        });
    }
};
