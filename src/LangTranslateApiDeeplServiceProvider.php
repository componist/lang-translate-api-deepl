<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl;

use Componist\LangTranslateApiDeepl\Commands\TranslateLangFile;
use Illuminate\Support\ServiceProvider;

class LangTranslateApiDeeplServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lang-translate-api-deepl.php', 'lang-translate-api-deepl');
    }

    public function boot(): void
    {
        $this->commands([
            TranslateLangFile::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/lang-translate-api-deepl.php' => config_path('lang-translate-api-deepl.php'),
        ], 'lang-translate-api-deepl');
    }
}
