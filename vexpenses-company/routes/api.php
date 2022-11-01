<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::delete('/company/{token}', [CompanyController::class, 'delete']);
