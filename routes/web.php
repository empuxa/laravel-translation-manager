<?php

use Empuxa\TranslationManager\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('translation-manager.route.middleware'))
    ->prefix(config('translation-manager.route.path'))->group(static function (): void {
        Route::get('/', [TranslationController::class, 'index'])
            ->name(config('translation-manager.route.path') . '.index');

        Route::put('/', [TranslationController::class, 'update'])
            ->name(config('translation-manager.route.path') . '.update');
    });
