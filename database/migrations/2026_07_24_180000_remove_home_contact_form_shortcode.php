<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        $this->cleanPagesTable();
        $this->cleanPageTranslationsTable();
    }

    public function down(): void
    {
        // The removed contact form contained site-specific admin content, so it is not restored automatically.
    }

    private function removeContactFormShortcode(string $content): string
    {
        $content = preg_replace('/\s*\[contact-form\b[^\]]*\]\s*\[\/contact-form\]\s*/is', "\n", $content) ?? $content;
        $content = preg_replace('/\s*\[contact-form\b[^\]]*\/\]\s*/is', "\n", $content) ?? $content;

        return trim(preg_replace("/\n{3,}/", "\n\n", $content) ?? $content);
    }

    private function cleanPagesTable(): void
    {
        if (! Schema::hasTable('pages') || ! Schema::hasColumn('pages', 'content')) {
            return;
        }

        DB::table('pages')
            ->where('content', 'like', '%[contact-form%')
            ->where('content', 'like', '%[hero-banner%')
            ->get(['id', 'content'])
            ->each(function (object $row): void {
                $content = $this->removeContactFormShortcode((string) $row->content);

                if ($content === $row->content) {
                    return;
                }

                DB::table('pages')
                    ->where('id', $row->id)
                    ->update(['content' => $content]);
            });
    }

    private function cleanPageTranslationsTable(): void
    {
        if (! Schema::hasTable('pages_translations') || ! Schema::hasColumn('pages_translations', 'content')) {
            return;
        }

        DB::table('pages_translations')
            ->where('content', 'like', '%[contact-form%')
            ->where('content', 'like', '%[hero-banner%')
            ->get(['lang_code', 'pages_id', 'content'])
            ->each(function (object $row): void {
                $content = $this->removeContactFormShortcode((string) $row->content);

                if ($content === $row->content) {
                    return;
                }

                DB::table('pages_translations')
                    ->where('lang_code', $row->lang_code)
                    ->where('pages_id', $row->pages_id)
                    ->update(['content' => $content]);
            });
    }
};
