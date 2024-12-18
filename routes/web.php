<?php

use App\Http\Controllers\ExampleOrderController;
use App\Http\Controllers\ValidationsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('validations', [ExampleOrderController::class, 'withoutFormRequest']);
