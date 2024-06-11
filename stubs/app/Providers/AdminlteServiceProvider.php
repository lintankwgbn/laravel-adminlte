<?php

namespace App\Providers;

use App\Actions\Adminlte\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Lintankwgbn\Adminlte\Adminlte;

class AdminlteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Adminlte::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Adminlte::defaultApiTokenPermissions(['read']);

        Adminlte::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
