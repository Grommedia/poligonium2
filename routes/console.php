<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('poligonium:page-cache-clear', function () {
    $paths = [
        public_path('page-cache'),
        storage_path('framework/page-cache'),
    ];

    foreach ($paths as $path) {
        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
            $this->line("Cleared: {$path}");

            continue;
        }

        $this->line("Already clear: {$path}");
    }

    $this->info('Poligonium public page cache cleared.');
})->purpose('Clear Poligonium static and framework page cache');
