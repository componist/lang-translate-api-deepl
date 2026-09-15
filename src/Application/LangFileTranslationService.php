<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl\Application;

use Componist\LangTranslateApiDeepl\Domain\LangArrayExporter;
use Illuminate\Support\Facades\Http;

final class LangFileTranslationService
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|false
     */
    public function translateArray(
        array $data,
        string $sourceLang,
        string $targetLang,
        string $apiKey,
        bool $dryRun = false,
        ?callable $onProgress = null,
    ): array|false {
        $translated = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                if (trim($value) === '') {
                    $translated[$key] = $value;

                    continue;
                }

                if ($onProgress !== null) {
                    $onProgress('line', $key, $value);
                }

                if ($dryRun) {
                    $translated[$key] = "[DRY-RUN: {$value}]";

                    continue;
                }

                $translatedValue = $this->translateText($value, $sourceLang, $targetLang, $apiKey);
                if ($translatedValue === false) {
                    return false;
                }

                $translated[$key] = $translatedValue;

                if ($onProgress !== null) {
                    $onProgress('translated', $key, $translatedValue);
                }

                continue;
            }

            if (is_array($value)) {
                $nested = $this->translateArray($value, $sourceLang, $targetLang, $apiKey, $dryRun, $onProgress);
                if ($nested === false) {
                    return false;
                }
                $translated[$key] = $nested;

                continue;
            }

            $translated[$key] = $value;
        }

        return $translated;
    }

    public function translateText(string $text, string $sourceLang, string $targetLang, string $apiKey): string|false
    {
        try {
            $response = Http::timeout((int) config('lang-translate-api-deepl.timeout_seconds', 30))
                ->withHeaders([
                    'Authorization' => "DeepL-Auth-Key {$apiKey}",
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post((string) config('lang-translate-api-deepl.api_url'), [
                    'text' => $text,
                    'source_lang' => strtoupper($sourceLang),
                    'target_lang' => strtoupper($targetLang),
                ]);

            if ($response->successful()) {
                $payload = $response->json();

                return is_array($payload) ? (string) ($payload['translations'][0]['text'] ?? '') : false;
            }

            return false;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function exportToPhpFile(array $data): string
    {
        return LangArrayExporter::toPhpFile($data);
    }
}
