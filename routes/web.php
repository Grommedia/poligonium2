<?php

use Illuminate\Support\Facades\Route;

Route::any('install/{any?}', static fn () => abort(404))->where('any', '.*');
