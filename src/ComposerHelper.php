<?php 

namespace Lintankwgbn\Adminlte;

use RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ComposerHelper
{
    /**
     * Determine processing the given commands.
     *
     * @param  array  $command
     * @param  callable  $callback
     * @return int
     */
    protected function processing(array $command, callable $callback = null)
    {
        $process = new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']);

        return $process->setTimeout(null)->run($callback);
    }
    
    /**
     * Determine if the given Composer package is installed.
     *
     * @param  string  $package
     * @return bool
     */
    public function hasComposerPackage(string $package)
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return array_key_exists($package, $packages['require'] ?? [])
            || array_key_exists($package, $packages['require-dev'] ?? []);
    }

    /**
     * Removes the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @param  string  $composer
     * @param  callable  $callback
     * @return bool
     */
    public function removeComposerPackages($packages, string $composer, callable $callback)
    {
        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'remove'];
        }

        $command = array_merge(
            $command ?? ['composer', 'remove'],
            is_array($packages) ? $packages : func_get_args()
        );

        return $this->processing($command, $callback) === 0;
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @param  string  $composer
     * @param  callable  $callback
     * @return bool
     */
    public function requireComposerPackages($packages, string $composer, callable $callback)
    {
        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        return $this->processing($command, $callback) === 0;
    }

    /**
     * Removes the given Composer Packages as "dev" dependencies.
     *
     * @param  mixed  $packages
     * @param  string  $composer
     * @param  callable  $callback
     * @return bool
     */
    public function removeComposerDevPackages($packages, string $composer, callable $callback)
    {
        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'remove', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'remove', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        return $this->processing($command, $callback) === 0;
    }

    /**
     * Install the given Composer Packages as "dev" dependencies.
     *
     * @param  mixed  $packages
     * @param  string  $composer
     * @param  callable  $callback
     * @return bool
     */
    public function requireComposerDevPackages($packages, string $composer, callable $callback)
    {
        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        return $this->processing($command, $callback) === 0;
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    public function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Get the path to the appropriate PHP binary.
     *
     * @return string
     */
    public function phpBinary()
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    public function runCommands($commands, $output)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write('    '.$line);
        });
    }
}
