<?php

namespace Lintankwgbn\Adminlte;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AdminlteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/adminlte.php', 'adminlte');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::viewPrefix('auth.');

        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureCommands();

        RedirectResponse::macro('banner', function ($message) {
            /** @var \Illuminate\Http\RedirectResponse $this */
            return $this->with('flash', [
                'bannerStyle' => 'success',
                'banner' => $message,
            ]);
        });

        RedirectResponse::macro('warningBanner', function ($message) {
            /** @var \Illuminate\Http\RedirectResponse $this */
            return $this->with('flash', [
                'bannerStyle' => 'warning',
                'banner' => $message,
            ]);
        });

        RedirectResponse::macro('dangerBanner', function ($message) {
            /** @var \Illuminate\Http\RedirectResponse $this */
            return $this->with('flash', [
                'bannerStyle' => 'danger',
                'banner' => $message,
            ]);
        });
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../stubs/config/adminlte.php' => config_path('adminlte.php'),
        ], 'adminlte-config');

        $this->publishes([
            __DIR__.'/../database/migrations/0001_01_01_000000_create_users_table.php' => database_path('migrations/0001_01_01_000000_create_users_table.php'),
        ], 'adminlte-migrations');

        $this->publishes([
            __DIR__.'/../routes/routes.php' => base_path('routes/adminlte.php'),
        ], 'adminlte-routes');
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Adminlte::$registersRoutes) {
            Route::group([
                'namespace' => 'Lintankwgbn\Adminlte\Http\Controllers',
                'domain' => config('adminlte.domain', null),
                'prefix' => config('adminlte.prefix', config('adminlte.path')),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
            });
        }
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    protected function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }

}
