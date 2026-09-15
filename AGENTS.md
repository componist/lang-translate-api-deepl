# AGENTS – Lang Translate API DeepL

## Zweck

Artisan-Command zum Übersetzen von Laravel-Sprachdateien (.php) via DeepL API — inkl. Dry-Run und verschachtelter Arrays.

## Grenzen & Abhängigkeiten

- Gehört rein: DeepL-Client, Translate-Command, Config
- Gehört nicht: allgemeine i18n-UI der App
- Status: **registriert**; API-Key über `DEEPL_API_KEY` in Config

## Struktur

```
src/Domain/LangArrayExporter.php
src/Application/LangFileTranslationService.php
src/Commands/TranslateLangFile.php
config/lang-translate-api-deepl.php
```

## Einbindung

- Provider nach Freigabe; Config über `.env` (`DEEPL_AUTH_KEY`)
- Command-Signatur siehe README

## Konventionen

- Dry-Run für erste Läufe
- Quell-/Zielsprache über Config

## Tests

Bei Registrierung: Unit-Tests mit HTTP-Fake für DeepL; kein Live-API-Key in Tests.

## Security

- API-Key nur in `.env`
- Keine User-supplied URLs an DeepL ohne Validierung
- Skill `security-audit` bei Command-Änderungen

## Do / Don’t

- Do: README-Beispiele für Dry-Run nutzen
- Don’t: Package ohne Key-Rotation-Plan in CI registrieren
