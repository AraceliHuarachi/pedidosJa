<?php


use App\Http\Controllers\ValidationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
route::get('/validations', [ValidationsController::class, 'validateOrdersData']);
