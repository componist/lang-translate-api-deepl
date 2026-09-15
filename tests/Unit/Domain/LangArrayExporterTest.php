<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl\Tests\Unit\Domain;

use Componist\LangTranslateApiDeepl\Domain\LangArrayExporter;
use PHPUnit\Framework\TestCase;

class LangArrayExporterTest extends TestCase
{
    public function test_to_php_file_exports_array(): void
    {
        $content = LangArrayExporter::toPhpFile(['greeting' => 'Hallo']);

        $this->assertStringStartsWith("<?php\n\nreturn ", $content);
        $this->assertStringContainsString("'greeting' => 'Hallo'", $content);
    }
}
