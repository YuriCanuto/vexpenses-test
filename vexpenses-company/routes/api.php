<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::post('/company/export', [CompanyController::class, 'export']);
Route::delete('/company/{token}', [CompanyController::class, 'delete']);

Route::get('local/temp/{path}', function (string $path){
    return Storage::disk('local')->download($path);
})->name('local.temp');
