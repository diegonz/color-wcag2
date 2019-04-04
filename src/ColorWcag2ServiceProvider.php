<?php

namespace Diegonz\ColorWcag2;

use Illuminate\Support\ServiceProvider;
use Diegonz\ColorWcag2\Compilers\TemplateCompiler;
use Diegonz\ColorWcag2\Compilers\CompilerInterface;
use Diegonz\ColorWcag2\Generators\TemplateGenerator;
use Diegonz\ColorWcag2\Generators\GeneratorInterface;
use Diegonz\ColorWcag2\Console\Commands\ColorWcag2GeneratorCommand;

/**
 * Class ColorWcag2ServiceProvider.
 */
class ColorWcag2ServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publishing config
            $this->publishes([
                __DIR__.'/../config/color-wcag2.php' => config_path('color-wcag2.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/color-wcag2.php', 'color-wcag2');

        // Register the main class to use with the facade
        $this->app->singleton('color-wcag2', static function () {
            return ColorWcag2::laravelFactory();
        });

        // Register Generator
        $this->app->bind(GeneratorInterface::class, TemplateGenerator::class);

        // Register Compiler
        $this->app->bind(CompilerInterface::class, TemplateCompiler::class);

        // Registering package CLI command
        if ($this->app->runningInConsole()) {
            $this->app->singleton(
                'command.color-wcag2.generate', static function ($app) {
                /* @var \Illuminate\Foundation\Application $app */
                $generator = $app->make(GeneratorInterface::class);

                return new ColorWcag2GeneratorCommand($app['config'], $generator);
            });
            $this->commands('command.color-wcag2.generate');
        }
    }
}
