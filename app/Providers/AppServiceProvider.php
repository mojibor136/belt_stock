<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use App\Models\Brand;
use App\Models\Group;
use App\Models\Size;
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
        // view()->share takes key => value array
        view()->share([
            'user'    => User::first(),
            'setting' => Setting::first(),
            'brands'  => Brand::all(),
            'groups'  => Group::all(),
            'sizes'   => Size::all(),
        ]);
    }
}
