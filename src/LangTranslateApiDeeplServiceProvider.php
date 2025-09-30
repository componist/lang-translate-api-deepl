<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl;

use Illuminate\Support\ServiceProvider;
use Componist\LangTranslateApiDeepl\Commands\TranslateLangFile;

class LangTranslateApiDeeplServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            TranslateLangFile::class,
        ]);
    }
}