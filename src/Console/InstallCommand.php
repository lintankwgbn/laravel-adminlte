<?php

namespace Lintankwgbn\Adminlte\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;
use function Illuminate\Filesystem\join_paths;
use function Laravel\Prompts\confirm;

#[AsCommand(name: 'adminlte:install')]
class InstallCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlte:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Adminlte components and resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Adminlte scaffolding installed start.');

        // Publish...
        $this->callSilent('vendor:publish', ['--tag' => 'adminlte-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'adminlte-migrations', '--force' => true]);

        $this->callSilent('vendor:publish', ['--tag' => 'fortify-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'fortify-support', '--force' => true]);

        if (! $this->migrationExists('users', 'add_two_factor_columns')) {
            $this->callSilent('vendor:publish', ['--tag' => 'fortify-migrations', '--force' => true]);
        }
        
        // Storage...
        $this->appendInFile("public_path('storage') => storage_path('app/public'),", [
            "storage_path('app/public/dist') => base_path('vendor/almasaeed2010/adminlte/dist'),",
            "storage_path('app/public/plugins') => base_path('vendor/almasaeed2010/adminlte/plugins'),",
        ], config_path('filesystems.php'));

        $this->callSilent('storage:link');

        $this->replaceInFile('/home', '/dashboard', config_path('fortify.php'));

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
            $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
        }

        $this->line('');

        // Fortify Provider...
        ServiceProvider::addProviderToBootstrapFile('App\Providers\FortifyServiceProvider');

        // Configure Session...
        $this->configureSession();

        $this->call('install:api', [
            '--without-migration-prompt' => true,
        ]);

        $this->line('');

        // Install bootstrap stack...
        $this->installBootstrapStack();

        $this->components->info('Adminlte scaffolding installed successfully.');
    }

    /**
     * Install the Bootstrap stack into the application.
     *
     * @return bool
     */
    protected function installBootstrapStack()
    {
        $filesystem = new Filesystem();

        // Directories...
        $filesystem->ensureDirectoryExists(app_path('Actions/Adminlte'));
        $filesystem->ensureDirectoryExists(app_path('Actions/Fortify'));
        $filesystem->ensureDirectoryExists(app_path('View/Components'));
        $filesystem->ensureDirectoryExists(resource_path('css'));
        $filesystem->ensureDirectoryExists(resource_path('markdown'));
        $filesystem->ensureDirectoryExists(resource_path('views/api'));
        $filesystem->ensureDirectoryExists(resource_path('views/auth'));
        $filesystem->ensureDirectoryExists(resource_path('views/components'));
        $filesystem->ensureDirectoryExists(resource_path('views/layouts'));
        $filesystem->ensureDirectoryExists(resource_path('views/profile'));

        // $filesystem->deleteDirectory(resource_path('sass'));

        // Terms Of Service / Privacy Policy...
        $filesystem->copy(__DIR__.'/../../stubs/resources/markdown/terms.md', resource_path('markdown/terms.md'));
        $filesystem->copy(__DIR__.'/../../stubs/resources/markdown/policy.md', resource_path('markdown/policy.md'));

        // Service Providers...
        $filesystem->copy(__DIR__.'/../../stubs/app/Providers/AdminlteServiceProvider.php', app_path('Providers/AdminlteServiceProvider.php'));
        ServiceProvider::addProviderToBootstrapFile('App\Providers\AdminlteServiceProvider');

        // Models...
        $filesystem->copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // Factories...
        $filesystem->copy(__DIR__.'/../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));

        // Actions...
        $filesystem->copy(__DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));
        $filesystem->copy(__DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));
        $filesystem->copy(__DIR__.'/../../stubs/app/Actions/Adminlte/DeleteUser.php', app_path('Actions/Adminlte/DeleteUser.php'));

        // Components...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/components', resource_path('views/components'));

        // View Components...
        $filesystem->copy(__DIR__.'/../../stubs/app/View/Components/AppLayout.php', app_path('View/Components/AppLayout.php'));
        $filesystem->copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));

        // Layouts...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));

        // Single Blade Views...
        $filesystem->copy(__DIR__.'/../../stubs/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));
        $filesystem->copy(__DIR__.'/../../stubs/resources/views/navigation-menu.blade.php', resource_path('views/navigation-menu.blade.php'));
        $filesystem->copy(__DIR__.'/../../stubs/resources/views/terms.blade.php', resource_path('views/terms.blade.php'));
        $filesystem->copy(__DIR__.'/../../stubs/resources/views/policy.blade.php', resource_path('views/policy.blade.php'));

        // Other Views...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/api', resource_path('views/api'));
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/profile', resource_path('views/profile'));
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/auth', resource_path('views/auth'));

        // Assets...
        $filesystem->copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));

        if (! Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
            $filesystem->append(base_path('routes/web.php'), $this->adminlteRouteDefinition());
        }

        $this->runDatabaseMigrations();

        return true;
    }

    /**
     * Get the route definition(s) that should be installed for Adminlte.
     *
     * @return string
     */
    protected function adminlteRouteDefinition()
    {
        return <<<'EOF'

Route::middleware([
    'auth:sanctum', config('adminlte.auth_session'), 'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

EOF;
    }

    /**
     * Configure the session driver for application.
     *
     * @return void
     */
    protected function configureSession()
    {
        $this->replaceInFile('SESSION_DRIVER=cookie', 'SESSION_DRIVER=database', base_path('.env'));
        $this->replaceInFile('SESSION_DRIVER=cookie', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    /**
     * Run the database migrations.
     *
     * @return void
     */
    protected function runDatabaseMigrations()
    {
        if (confirm('New database migrations were added. Would you like to re-run your migrations?', true)) {
            (new Process([$this->phpBinary(), 'artisan', 'migrate:fresh', '--force'], base_path()))
                ->setTimeout(null)
                ->run(function ($type, $output) {
                    $this->output->write($output);
                });
        }
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    protected function appendInFile(string $offset, array $lines, string $path, $target = null)
    {
        $content = file_get_contents($path);
        $array = [];

        foreach (file($path) as $inline) {
            $array[] = $inline;

            if (str_contains($inline, $offset)) {
                foreach ($lines as $line) {
                    if (! str_contains($content, $line)) {
                        $array[] = str_replace($offset, $line, $inline);
                    }
                }
            }
        }

        if (is_null($target)) {
            file_put_contents($path, implode('', $array));
        } else {
            file_put_contents($target, implode('', $array));
        }
    }

    protected function removeInFile(array $lines, string $path, $target = null)
    {
        $array = [];

        foreach (file($path) as $inline) {
            foreach ($lines as $line) {
                if (str_contains($inline, $line)) {
                    $inline = '';
                }
            }
            $array[] = $inline;
        }

        if (is_null($target)) {
            file_put_contents($path, implode('', $array));
        } else {
            file_put_contents($target, implode('', $array));
        }
    }

    /**
     * Determine whether a migration for the table already exists.
     *
     * @param  string  $name
     * @param  string  $table
     * @return bool
     */
    protected function migrationExists($table, $name = '')
    {
        return count((new Filesystem)->glob(
            join_paths($this->laravel->databasePath('migrations'), '*_*_*_*_'. $name .'_to_'. $table .'_table.php')
        )) !== 0;
    }
}
