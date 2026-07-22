<?php

use Illuminate\Support\Facades\Route;

Route::any('install/{any?}', static fn () => abort(404))->where('any', '.*');

Route::get('poligonium/csrf-token', static fn () => response()->json([
    'token' => csrf_token(),
]))->name('poligonium.csrf-token');
