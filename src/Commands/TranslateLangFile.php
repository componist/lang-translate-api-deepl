<?php

namespace Componist\LangTranslateApiDeepl\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class TranslateLangFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:lang-file 
                            {file : Path to the language file to translate}
                            {--source=de : Source language code}
                            {--target=en : Target language code}
                            {--dry-run : Show what would be translated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate strings in a language file using DeepL API';

    /**
     * DeepL API endpoint
     */
    private const DEEPL_API_URL = 'https://api-free.deepl.com/v2/translate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $sourceLang = $this->option('source');
        $targetLang = $this->option('target');
        $dryRun = $this->option('dry-run');

        // Validate file exists
        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        // Check for DeepL API key
        $apiKey = env('DEEPL_API_KEY');
        if (!$apiKey) {
            $this->error('DeepL API key not configured. Please set DEEPL_API_KEY in your .env file.');
            return 1;
        }

        // Load the language file
        $translations = require $filePath;
        
        if (!is_array($translations)) {
            $this->error('Invalid language file format. Expected array.');
            return 1;
        }

        $this->info("Translating from {$sourceLang} to {$targetLang}");
        $this->info("File: {$filePath}");
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Translate strings
        $translated = $this->translateStrings($translations, $sourceLang, $targetLang, $apiKey, $dryRun);

        if ($translated === false) {
            $this->error('Translation failed');
            return 1;
        }

        if (!$dryRun) {
            // Save the translated file
            $this->saveTranslatedFile($filePath, $translated);
            $this->info('Translation completed successfully!');
        } else {
            $this->info('Dry run completed. Use without --dry-run to apply changes.');
        }

        return 0;
    }

    /**
     * Recursively translate strings in the array
     */
    private function translateStrings(array $data, string $sourceLang, string $targetLang, string $apiKey, bool $dryRun): array|false
    {
        $translated = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Only translate non-empty strings
                if (!empty(trim($value))) {
                    $this->line("Translating: {$key} = {$value}");
                    
                    if (!$dryRun) {
                        $translatedValue = $this->translateText($value, $sourceLang, $targetLang, $apiKey);
                        
                        if ($translatedValue === false) {
                            $this->error("Failed to translate: {$value}");
                            return false;
                        }
                        
                        $translated[$key] = $translatedValue;
                        $this->line("→ {$translatedValue}");
                    } else {
                        $translated[$key] = "[WOULD TRANSLATE: {$value}]";
                    }
                } else {
                    $translated[$key] = $value;
                }
            } elseif (is_array($value)) {
                // Recursively translate nested arrays
                $translated[$key] = $this->translateStrings($value, $sourceLang, $targetLang, $apiKey, $dryRun);
                
                if ($translated[$key] === false) {
                    return false;
                }
            } else {
                // Keep non-string values as-is
                $translated[$key] = $value;
            }
        }

        return $translated;
    }

    /**
     * Translate a single text using DeepL API
     */
    private function translateText(string $text, string $sourceLang, string $targetLang, string $apiKey): string|false
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "DeepL-Auth-Key {$apiKey}",
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post(self::DEEPL_API_URL, [
                    'text' => $text,
                    'source_lang' => strtoupper($sourceLang),
                    'target_lang' => strtoupper($targetLang),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['translations'][0]['text'] ?? false;
            } else {
                $this->error("DeepL API error: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            $this->error("Translation error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Save the translated array back to the file
     */
    private function saveTranslatedFile(string $filePath, array $data): void
    {
        $content = "<?php\n\nreturn " . $this->arrayToString($data) . ";\n";
        File::put($filePath, $content);
    }

    /**
     * Convert array to PHP string representation
     */
    private function arrayToString(array $data, int $indent = 0): string
    {
        $indentStr = str_repeat('    ', $indent);
        $lines = ['['];
        
        foreach ($data as $key => $value) {
            $keyStr = is_string($key) ? "'{$key}'" : $key;
            
            if (is_array($value)) {
                $valueStr = $this->arrayToString($value, $indent + 1);
                $lines[] = "{$indentStr}    {$keyStr} => {$valueStr},";
            } else {
                $valueStr = is_string($value) ? "'" . addslashes($value) . "'" : var_export($value, true);
                $lines[] = "{$indentStr}    {$keyStr} => {$valueStr},";
            }
        }
        
        $lines[] = "{$indentStr}]";
        return implode("\n", $lines);
    }
}