# Lang Translate API DeepL

Ein Laravel Package zum automatischen Übersetzen von Sprachdateien mit der DeepL API.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-10%2B-red.svg)](https://laravel.com/)

## 📋 Inhaltsverzeichnis

- [Überblick](#überblick)
- [Features](#features)
- [Installation](#installation)
- [Konfiguration](#konfiguration)
- [Verwendung](#verwendung)
- [Beispiele](#beispiele)
- [API-Referenz](#api-referenz)
- [Unterstützte Sprachen](#unterstützte-sprachen)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)
- [Entwicklung](#entwicklung)
- [Lizenz](#lizenz)

## 🎯 Überblick

**Lang Translate API DeepL** ist ein Laravel Package, das die automatische Übersetzung von Laravel-Sprachdateien (.php) mit der DeepL API ermöglicht. Es bietet einen Artisan-Command, der verschachtelte Arrays unterstützt und einen sicheren Dry-Run-Modus für Tests bereitstellt.

### Hauptfunktionen

- ✅ **Automatische Übersetzung** von Laravel-Sprachdateien
- ✅ **DeepL API Integration** für hochwertige Übersetzungen
- ✅ **Verschachtelte Arrays** werden rekursiv verarbeitet
- ✅ **Dry-Run Modus** für sichere Tests
- ✅ **Flexible Sprachkonfiguration** (Quell- und Zielsprachen)
- ✅ **Robuste Fehlerbehandlung** mit Timeout-Schutz
- ✅ **Laravel Service Provider** für einfache Integration

## 🚀 Features

### Core Features

| Feature | Beschreibung |
|---------|-------------|
| **String-Übersetzung** | Übersetzt ausschließlich String-Werte, lässt andere Datentypen unverändert |
| **Rekursive Verarbeitung** | Unterstützt mehrstufige Array-Strukturen |
| **Dry-Run Modus** | Sichere Tests ohne Dateiänderungen |
| **Konfigurierbare Sprachen** | Flexible Quell- und Zielsprachen |
| **API-Integration** | Direkte DeepL API Integration mit Fehlerbehandlung |

### Sicherheitsfeatures

| Feature | Beschreibung |
|---------|-------------|
| **Validierung** | Überprüft Dateiexistenz und Format |
| **API-Key Validierung** | Überprüft DeepL API Key Konfiguration |
| **Fehlerbehandlung** | Graceful Handling von Netzwerk- und API-Fehlern |
| **Timeout-Schutz** | 30-Sekunden Timeout für API-Requests |

## 📦 Installation

### 1. Package installieren

```bash
composer require componist/lang-translate-api-deepl
```

### 2. Service Provider registrieren

Der Service Provider wird automatisch registriert. Falls Sie Laravel 5.5+ verwenden, ist keine manuelle Registrierung erforderlich.

### 3. DeepL API Key konfigurieren

Fügen Sie Ihren DeepL API Key zur `config/services.php` hinzu:

```php
// config/services.php
'deepl' => [
    'api_key' => env('DEEPL_API_KEY'),
],
```

### 4. Umgebungsvariablen setzen

Fügen Sie Ihren DeepL API Key zur `.env` Datei hinzu:

```env
DEEPL_API_KEY=your_deepl_api_key_here
```

### 5. DeepL API Key erhalten

1. Registrieren Sie sich auf [DeepL API](https://www.deepl.com/pro-api)
2. Erstellen Sie einen kostenlosen API Key (500.000 Zeichen/Monat)
3. Fügen Sie den Key zur `.env` Datei hinzu

## ⚙️ Konfiguration

### Umgebungsvariablen

| Variable | Beschreibung | Standard |
|----------|-------------|----------|
| `DEEPL_API_KEY` | Ihr DeepL API Key | - |

### Service Provider Konfiguration

```php
// config/services.php
return [
    // ... andere Services
    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],
];
```

## 🎮 Verwendung

### Artisan Command

Das Package registriert einen Artisan-Command `translate:lang-file`:

```bash
php artisan translate:lang-file [options] [--] <file>
```

### Parameter

| Parameter | Beschreibung | Erforderlich |
|-----------|-------------|--------------|
| `file` | Pfad zur Sprachdatei, die übersetzt werden soll | ✅ |

### Optionen

| Option | Beschreibung | Standard | Erforderlich |
|--------|-------------|----------|--------------|
| `--source[=SOURCE]` | Quellsprache (ISO-Code) | `de` | ❌ |
| `--target[=TARGET]` | Zielsprache (ISO-Code) | `en` | ❌ |
| `--dry-run` | Zeigt was übersetzt würde, ohne Änderungen vorzunehmen | - | ❌ |

## 📝 Beispiele

### 1. Grundlegende Verwendung

```bash
# Deutsch → Englisch (Standard)
php artisan translate:lang-file lang/en/messages.php

# Mit expliziten Sprachen
php artisan translate:lang-file lang/en/messages.php --source=de --target=en
```

### 2. Verschiedene Sprachkombinationen

```bash
# Deutsch → Französisch
php artisan translate:lang-file lang/fr/messages.php --source=de --target=fr

# Deutsch → Spanisch
php artisan translate:lang-file lang/es/messages.php --source=de --target=es

# Englisch → Deutsch
php artisan translate:lang-file lang/de/messages.php --source=en --target=de
```

### 3. Dry-Run Modus

```bash
# Test ohne Änderungen
php artisan translate:lang-file lang/en/messages.php --dry-run
```

### 4. Hilfe anzeigen

```bash
php artisan translate:lang-file --help
```

## 🔄 Beispiel-Übersetzung

### Eingabe (`lang/de/messages.php`):

```php
<?php
return [
    'welcome' => 'Willkommen',
    'user' => [
        'profile' => 'Profil',
        'settings' => 'Einstellungen',
        'logout' => 'Abmelden',
    ],
    'validation' => [
        'required' => 'Dieses Feld ist erforderlich',
        'email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
    ],
];
```

### Ausgabe (nach Übersetzung zu Englisch):

```php
<?php
return [
    'welcome' => 'Welcome',
    'user' => [
        'profile' => 'Profile',
        'settings' => 'Settings',
        'logout' => 'Logout',
    ],
    'validation' => [
        'required' => 'This field is required',
        'email' => 'Please enter a valid email address',
    ],
];
```

## 🌍 Unterstützte Sprachen

### Quellsprachen

| Code | Sprache | Code | Sprache |
|------|---------|------|---------|
| `DE` | Deutsch | `EN` | Englisch |
| `FR` | Französisch | `ES` | Spanisch |
| `IT` | Italienisch | `PT` | Portugiesisch |
| `RU` | Russisch | `PL` | Polnisch |
| `NL` | Niederländisch | `JA` | Japanisch |
| `ZH` | Chinesisch | - | - |

### Zielsprachen

Alle Quellsprachen plus:

| Code | Sprache | Code | Sprache |
|------|---------|------|---------|
| `BG` | Bulgarisch | `CS` | Tschechisch |
| `DA` | Dänisch | `EL` | Griechisch |
| `ET` | Estnisch | `FI` | Finnisch |
| `HU` | Ungarisch | `LT` | Litauisch |
| `LV` | Lettisch | `RO` | Rumänisch |
| `SK` | Slowakisch | `SL` | Slowenisch |
| `SV` | Schwedisch | - | - |

## 📚 API-Referenz

### Command Signatur

```php
protected $signature = 'translate:lang-file 
                        {file : Path to the language file to translate}
                        {--source=de : Source language code}
                        {--target=en : Target language code}
                        {--dry-run : Show what would be translated without making changes}';
```

### Methoden

#### `handle()`
Führt den Übersetzungsprozess aus.

#### `translateStrings(array $data, string $sourceLang, string $targetLang, string $apiKey, bool $dryRun)`
Rekursiv übersetzt Strings in einem Array.

#### `translateText(string $text, string $sourceLang, string $targetLang, string $apiKey)`
Übersetzt einen einzelnen Text mit der DeepL API.

#### `saveTranslatedFile(string $filePath, array $data)`
Speichert das übersetzte Array zurück in die Datei.

## 💡 Best Practices

### 1. Backup erstellen

```bash
# Backup vor Übersetzung erstellen
cp lang/de/messages.php lang/de/messages.php.backup
```

### 2. Dry-Run verwenden

```bash
# Immer zuerst mit --dry-run testen
php artisan translate:lang-file lang/de/messages.php --dry-run
```

### 3. Schrittweise Übersetzung

```bash
# Übersetzen Sie Dateien einzeln für bessere Kontrolle
php artisan translate:lang-file lang/de/messages.php
php artisan translate:lang-file lang/de/validation.php
php artisan translate:lang-file lang/de/auth.php
```

### 4. Qualitätskontrolle

- ✅ Überprüfen Sie die Übersetzungen nach der Ausführung
- ✅ Testen Sie die Anwendung mit den neuen Übersetzungen
- ✅ Korrigieren Sie bei Bedarf manuell
- ✅ Verwenden Sie Version Control für Änderungen

### 5. Performance-Optimierung

- ✅ Übersetzen Sie nur geänderte Dateien
- ✅ Verwenden Sie Batch-Processing für große Projekte
- ✅ Überwachen Sie API-Limits

## 🔧 Troubleshooting

### Häufige Fehler und Lösungen

#### 1. "DeepL API key not configured"

**Problem**: API Key ist nicht konfiguriert.

**Lösung**:
```bash
# .env Datei überprüfen
cat .env | grep DEEPL_API_KEY

# Falls nicht vorhanden, hinzufügen:
echo "DEEPL_API_KEY=your_key_here" >> .env
```

#### 2. "File not found"

**Problem**: Die angegebene Datei existiert nicht.

**Lösung**:
```bash
# Dateipfad überprüfen
ls -la lang/de/messages.php

# Korrekten Pfad verwenden
php artisan translate:lang-file lang/de/messages.php
```

#### 3. "Invalid language file format"

**Problem**: Die Datei enthält kein gültiges PHP-Array.

**Lösung**:
```php
// Stellen Sie sicher, dass die Datei so aussieht:
<?php
return [
    'key' => 'value',
];
```

#### 4. "DeepL API error"

**Problem**: API-Fehler oder Limits erreicht.

**Lösung**:
- API Key überprüfen
- Kontingent überprüfen
- Netzwerkverbindung testen

### Command nicht gefunden

```bash
# Command registrieren
php artisan list | grep translate

# Falls nicht gefunden, Cache leeren
php artisan config:clear
php artisan cache:clear
```

### Permission-Fehler

```bash
# Schreibrechte überprüfen
ls -la lang/de/

# Rechte setzen (falls nötig)
chmod 755 lang/de/
chmod 644 lang/de/*.php
```

### API-Limits

| Plan | Limit | Kosten |
|------|-------|--------|
| **Free** | 500.000 Zeichen/Monat | Kostenlos |
| **Pro** | Höhere Limits | Ab €5.99/Monat |

## 🛠️ Entwicklung

### Projektstruktur

```
src/
├── Commands/
│   └── TranslateLangFile.php          # Artisan Command
└── LangTranslateApiDeeplServiceProvider.php  # Service Provider
```

### Abhängigkeiten

- **PHP**: >= 8.1
- **Laravel**: >= 10.0
- **Componist Core**: ^1.0.0

### Entwicklungsumgebung

```bash
# Repository klonen
git clone https://github.com/componist/lang-translate-api-deepl.git

# Abhängigkeiten installieren
composer install

# Tests ausführen
composer test
```

### Beitragen

1. Fork des Repositories
2. Feature-Branch erstellen
3. Änderungen committen
4. Pull Request erstellen

## 📄 Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Siehe [LICENSE](LICENSE) für Details.

## 🤝 Support

Bei Problemen oder Fragen:

1. **Laravel Logs überprüfen**: `storage/logs/laravel.log`
2. **Dry-Run Modus verwenden**: `--dry-run` für Tests
3. **DeepL API Dokumentation**: [DeepL API Docs](https://www.deepl.com/docs-api)
4. **Issues erstellen**: [GitHub Issues](https://github.com/componist/lang-translate-api-deepl/issues)

## 📞 Kontakt

- **Entwickler**: Componist Developer
- **E-Mail**: info@componist.dev
- **Website**: [componist.dev](https://componist.dev)

---

**⚠️ Wichtiger Hinweis**: Dieser Command überschreibt die ursprüngliche Datei. Erstellen Sie immer ein Backup vor der Verwendung.

**🔒 Sicherheit**: Bewahren Sie Ihren DeepL API Key sicher auf und teilen Sie ihn nicht öffentlich.