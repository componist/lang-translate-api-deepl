<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LangTranslateApiDeeplServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'../../config/name.php', 'name');

        

        

        

        

        // Livewire::component('dynamic-api.index', Index::class);

    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            //$schedule->command('command:autoClearSystem')->dailyAt('01:00');

            // for development and testing
            // $schedule->command('command:autoClearSystem')->everyMinute();
        });

        $this->publishes([
            __DIR__.'/../config/lang-translate-api-deepl.php' => config_path('lang-translate-api-deepl.php'),
        ]);

        // blade componente
        $this->bootBladeComponents();

        // livewire componente
        $this->bootLivewireComponents();

    }

    private function bootBladeComponents(): void
    {
        foreach (config('name.components', []) as $alias => $component) {
            Blade::component(config('name.prefix').$alias, $component);
        }
    }

    private function bootLivewireComponents(): void
    {
        foreach (config('name.livewire', []) as $alias => $component) {
            Livewire::component(config('name.prefix').$alias, $component);
        }
    }


}