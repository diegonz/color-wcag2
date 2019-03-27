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
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'color-wcag2');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'color-wcag2');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/color-wcag2.php' => config_path('color-wcag2.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/color-wcag2'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/color-wcag2'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/color-wcag2'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
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

        // Register Command
        $this->app->singleton(
            'command.color-wcag2.generate',
            static function ($app) {
                $config = $app['config'];
                $generator = $app->make(GeneratorInterface::class);

                return new ColorWcag2GeneratorCommand($config, $generator);
            }
        );
        $this->commands('command.color-wcag2.generate');
    }
}
