<?php

use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::get('/templates/{id}', [TemplateController::class, 'show']);
    Route::get('/templates/categories', [TemplateController::class, 'categories']);
    // If you want API routes
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('api.search.suggestions');
});