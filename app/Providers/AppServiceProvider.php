<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Auth\AuthInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        DB::listen(function ($query) {
//            Log::debug($query->sql, $query->bindings);
            if (env('APP_ENV') == 'local') {
                Log::debug(vsprintf(str_replace("?", "'%s'", $query->sql), $query->bindings));//替换sql中的变量
            }
        });
        //
        $this->app->bind(AuthInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
