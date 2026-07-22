<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plg_course_purchases', function (Blueprint $table): void {
            if (! Schema::hasColumn('plg_course_purchases', 'provider_invoice_id')) {
                $table->string('provider_invoice_id')->nullable()->after('payment_reference');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'provider_page_url')) {
                $table->text('provider_page_url')->nullable()->after('provider_invoice_id');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'provider_status')) {
                $table->string('provider_status', 60)->nullable()->after('provider_page_url');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'provider_modified_at')) {
                $table->timestamp('provider_modified_at')->nullable()->after('provider_status');
            }

            if (! Schema::hasColumn('plg_course_purchases', 'provider_payload')) {
                $table->json('provider_payload')->nullable()->after('provider_modified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plg_course_purchases', function (Blueprint $table): void {
            foreach ([
                'provider_invoice_id',
                'provider_page_url',
                'provider_status',
                'provider_modified_at',
                'provider_payload',
            ] as $column) {
                if (Schema::hasColumn('plg_course_purchases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
