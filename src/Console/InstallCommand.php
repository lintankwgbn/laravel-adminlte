<?php

namespace Lintankwgbn\Adminlte\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\PhpExecutableFinder;
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

    protected $filesystem;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Adminlte scaffolding installed start.');

        $values = [
            // Publish...
            'Publish adminlte config' => function () {
                return $this->callSilent('vendor:publish', [
                    '--tag' => 'adminlte-config', 
                    '--force' => true,
                ]) == 0;
            },
            'Publish adminlte migrations' => function () {
                return $this->callSilent('vendor:publish', [
                    '--tag' => 'adminlte-migrations', 
                    '--force' => true,
                ]) == 0;
            },
            'Publish fortify config' => function () {
                return $this->callSilent('vendor:publish', [
                    '--tag' => 'fortify-config', 
                    '--force' => true,
                ]) == 0;
            },
            'Publish fortify support' => function () {
                return $this->callSilent('vendor:publish', [
                    '--tag' => 'fortify-support', 
                    '--force' => true,
                ]) == 0;
            },
        ];

        if (! $this->migrationExists('users', 'add_two_factor_columns')) {
            $values = array_merge($values, [
                'Publish fortify migrations' => function () {
                    return $this->callSilent('vendor:publish', [
                        '--tag' => 'fortify-migrations', 
                        '--force' => true,
                    ]) == 0;
                },
            ]);
        }
        
        $values = array_merge($values, [
            // Fortify Provider...
            'Add Fortify Service Provider' => function () {
                return ServiceProvider::addProviderToBootstrapFile('App\Providers\FortifyServiceProvider');
            },
            // Add Configure Session...
            'Add Configure Session' => function () {
                return $this->configureSession();
            },
        ]);

        $values = array_merge($values, [
            // Add Storage links...
            'Add data storage links' => function () {
                return $this->appendInFile("public_path('storage') => storage_path('app/public'),", [
                    "storage_path('app/public/dist') => base_path('vendor/almasaeed2010/adminlte/dist'),",
                    "storage_path('app/public/plugins') => base_path('vendor/almasaeed2010/adminlte/plugins'),",
                ], config_path('filesystems.php'));
            },
            'Publish storage links' => function () {
                return $this->callSilent('storage:link') == 0;
            },
            'Replace file fortify php' => function () {
                return $this->replaceInFile('/home', '/dashboard', config_path('fortify.php'));
            },
        ]);

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
            $values = array_merge($values, [
                'Replace file welcome blade php' => function () {
                    return $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
                },
            ]);
        }

        if (! file_exists(base_path('routes/api.php'))) {
            // Fortify migration...
            $values = array_merge($values, [
                'Install route api' => function () {
                    return $this->callSilent('install:api', ['--without-migration-prompt' => true]) == 0;
                },
            ]);
        }
        
        collect($values)->each(fn ($task, $description) => $this->components->task($description, $task));
        
        // Install bootstrap stack...
        $this->installBootstrapStack();

        $this->components->info('Adminlte scaffolding installed successfully.');

        // $this->line('');

        $this->runDatabaseMigrations();

    }

    /**
     * Install the Bootstrap stack into the application.
     *
     * @return void
     */
    protected function installBootstrapStack()
    {
        $this->components->info('Install the Bootstrap stack into the application.');
        
        $this->filesystem = new Filesystem();

        // Directories...
        $this->filesystem->ensureDirectoryExists(app_path('Actions/Adminlte'));
        $this->filesystem->ensureDirectoryExists(app_path('Actions/Fortify'));
        $this->filesystem->ensureDirectoryExists(app_path('View/Components'));
        $this->filesystem->ensureDirectoryExists(resource_path('css'));
        $this->filesystem->ensureDirectoryExists(resource_path('markdown'));
        $this->filesystem->ensureDirectoryExists(resource_path('views/api'));
        $this->filesystem->ensureDirectoryExists(resource_path('views/auth'));
        $this->filesystem->ensureDirectoryExists(resource_path('views/components'));
        $this->filesystem->ensureDirectoryExists(resource_path('views/layouts'));
        $this->filesystem->ensureDirectoryExists(resource_path('views/profile'));

        $values = [
            'Add Adminlte Service Provider' => function () {
                return ServiceProvider::addProviderToBootstrapFile('App\Providers\AdminlteServiceProvider');
            },
            // $this->filesystem->deleteDirectory(resource_path('sass'));
            // Terms Of Service...
            'Copy markdown terms file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/markdown/terms.md', resource_path('markdown/terms.md'));
            },
            // Privacy Policy...
            'Copy markdown policy file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/markdown/policy.md', resource_path('markdown/policy.md'));
            },
            // Service Providers...
            'Copy AdminlteServiceProvider file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/Providers/AdminlteServiceProvider.php', app_path('Providers/AdminlteServiceProvider.php'));
            },
            // Models...
            'Copy User Models file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));
            },
            // Factories...
            'Copy UserFactory file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));
            },
            // Actions...
            'Copy CreateNewUser file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/Actions/Fortify/CreateNewUser.php', app_path('Actions/Fortify/CreateNewUser.php'));
            },
            'Copy UpdateUserProfileInformation file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/Actions/Fortify/UpdateUserProfileInformation.php', app_path('Actions/Fortify/UpdateUserProfileInformation.php'));
            },
            'Copy DeleteUser file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/Actions/Adminlte/DeleteUser.php', app_path('Actions/Adminlte/DeleteUser.php'));
            },
            // View Components...
            'Copy AppLayout file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/View/Components/AppLayout.php', app_path('View/Components/AppLayout.php'));
            },
            'Copy GuestLayout file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));
            },
            // Single Blade Views...
            'Copy dashboard blade file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));
            },
            'Copy navigation-menu blade file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/views/navigation-menu.blade.php', resource_path('views/navigation-menu.blade.php'));
            },
            'Copy terms blade file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/views/terms.blade.php', resource_path('views/terms.blade.php'));
                },
            'Copy policy blade file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/views/policy.blade.php', resource_path('views/policy.blade.php'));
            },
            // Assets...
            'Copy app css file' => function () {
                return $this->filesystem->copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));
            },
            // Components...
            'Copy components directory' => function () {
                return $this->filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/components', resource_path('views/components'));
            },
            // Layouts...
            'Copy layouts directory' => function () {
                return $this->filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));
            },
            // Other Views...
            'Copy auth directory' => function () {
                return $this->filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/auth', resource_path('views/auth'));
            },
            'Copy api directory' => function () {
                return $this->filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/api', resource_path('views/api'));
            },
            'Copy profile directory' => function () {
                return $this->filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/profile', resource_path('views/profile'));
            },
        ];

        if (! Str::contains(file_get_contents(base_path('routes/web.php')), "'/dashboard'")) {
            $values = array_merge($values, [
                'Append routes web file' => function () {
                    return $this->filesystem->append(base_path('routes/web.php'), $this->adminlteRouteDefinition()) == 0;
                },
            ]);
        }

        collect($values)->each(fn ($task, $description) => $this->components->task($description, $task));
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
     * @return bool
     */
    protected function configureSession()
    {
        $this->replaceInFile('SESSION_DRIVER=cookie', 'SESSION_DRIVER=database', base_path('.env'));
        return $this->replaceInFile('SESSION_DRIVER=cookie', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    /**
     * Run the database migrations.
     *
     * @return int
     */
    protected function runDatabaseMigrations()
    {
        // $label = 'New database migrations were added. Would you like to re-run your migrations?';
        // $command = [$this->phpBinary(), 'artisan', 'migrate:fresh', '--force', '--seed'];

        // if (confirm([$this->phpBinary(), 'artisan', 'migrate:fresh', '--force', '--seed'])) {
        //     return (new Process($command, base_path()))->setTimeout(null)->run(function ($type, $output) {
        //         $this->output->write($output);
        //     });
            return $this->call('migrate:fresh', ['--force' => true, '--seed' => true]);
        // }

        // return 1;
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return bool
     */
    protected function replaceInFile(string $search, string $replace, string $path)
    {
        return file_put_contents($path, str_replace($search, $replace, file_get_contents($path))) !== false;
    }

    /**
     * Append a given string within a given file.
     *
     * @param  string  $offset
     * @param  array   $lines
     * @param  string  $path
     * @param  bool    $target
     * @return bool
     */
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
            return file_put_contents($path, implode('', $array)) !== false;
        }
        return file_put_contents($target, implode('', $array)) !== false;
    }

    /**
     * Remove a given string within a given file.
     *
     * @param  array   $lines
     * @param  string  $path
     * @param  bool    $target
     * @return bool
     */
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
            return file_put_contents($path, implode('', $array)) !== false;
        }
        return file_put_contents($target, implode('', $array)) !== false;
    }

    /**
     * Get the path to the appropriate PHP binary.
     *
     * @return string
     */
    public function phpBinary()
    {
        return (new PhpExecutableFinder)->find(false) ?: 'php';
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
        $paths = '*_*_*_*_'. $name .'_to_'. $table .'_table.php';
        $pattern = join_paths(database_path('migrations'), $paths);

        return count((new Filesystem)->glob($pattern)) !== 0;
    }
}
