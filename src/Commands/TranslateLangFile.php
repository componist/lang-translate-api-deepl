<?php



declare(strict_types=1);



namespace Componist\LangTranslateApiDeepl\Commands;



use Componist\LangTranslateApiDeepl\Application\LangFileTranslationService;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;



class TranslateLangFile extends Command

{

    protected $signature = 'translate:lang-file

                            {file : Pfad zur Sprachdatei}

                            {--source=de : Quellsprache}

                            {--target=en : Zielsprache}

                            {--dry-run : Nur anzeigen, nicht speichern}';



    protected $description = 'Sprachdatei per DeepL API übersetzen';



    public function handle(LangFileTranslationService $translator): int

    {

        $filePath = (string) $this->argument('file');

        $sourceLang = (string) $this->option('source');

        $targetLang = (string) $this->option('target');

        $dryRun = (bool) $this->option('dry-run');



        if (! File::exists($filePath)) {

            $this->error("Datei nicht gefunden: {$filePath}");



            return self::FAILURE;

        }



        $apiKey = (string) config('lang-translate-api-deepl.api_key', '');

        if ($apiKey === '') {

            $this->error('DeepL API-Schlüssel fehlt. Bitte DEEPL_API_KEY in der .env setzen.');



            return self::FAILURE;

        }



        $translations = require $filePath;

        if (! is_array($translations)) {

            $this->error('Ungültiges Dateiformat. Array erwartet.');



            return self::FAILURE;

        }



        $this->info("Übersetze von {$sourceLang} nach {$targetLang}");

        $this->info("Datei: {$filePath}");



        if ($dryRun) {

            $this->warn('Dry-Run – es werden keine Änderungen gespeichert.');

        }



        $translated = $translator->translateArray(

            $translations,

            $sourceLang,

            $targetLang,

            $apiKey,

            $dryRun,

            function (string $event, string $key, string $value) use ($dryRun): void {

                if ($event === 'line') {

                    $this->line("Übersetze: {$key} = {$value}");



                    return;

                }



                if (! $dryRun) {

                    $this->line("→ {$value}");

                }

            }

        );



        if ($translated === false) {

            $this->error('Übersetzung fehlgeschlagen.');



            return self::FAILURE;

        }



        if (! $dryRun) {

            File::put($filePath, $translator->exportToPhpFile($translated));

            $this->info('Übersetzung wurde gespeichert.');

        } else {

            $this->info('Dry-Run abgeschlossen. Ohne --dry-run werden Änderungen geschrieben.');

        }



        return self::SUCCESS;

    }

}


