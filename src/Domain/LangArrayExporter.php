<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl\Domain;

final class LangArrayExporter
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function toPhp(array $data, int $indent = 0): string
    {
        $indentStr = str_repeat('    ', $indent);
        $lines = ['['];

        foreach ($data as $key => $value) {
            $keyStr = is_string($key) ? "'{$key}'" : (string) $key;

            if (is_array($value)) {
                $valueStr = self::toPhp($value, $indent + 1);
                $lines[] = "{$indentStr}    {$keyStr} => {$valueStr},";
            } else {
                $valueStr = is_string($value) ? "'".addslashes($value)."'" : var_export($value, true);
                $lines[] = "{$indentStr}    {$keyStr} => {$valueStr},";
            }
        }

        $lines[] = "{$indentStr}]";

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toPhpFile(array $data): string
    {
        return "<?php\n\nreturn ".self::toPhp($data).";\n";
    }
}
