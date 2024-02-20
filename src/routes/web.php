<?php

use Countrycodevendor\Countrycode\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

Route::get('/countryStoreData', [CountryController::class, 'store']);
Route::get('/country', [CountryController::class, 'index']);
Route::post('/validate', [CountryController::class, 'checkValidation'])->name('validate');
