<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('multiple_of_five', function ($attribute, $value, $parameters, $validator) {
            return $value % 5 === 0;
        }, __('validation.multiple_of_five'));
    }
}
