<?php

declare(strict_types=1);

namespace Componist\LangTranslateApiDeepl\Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class TranslateLangFileFeatureTest extends TestCase
{
    public function test_command_fails_without_api_key(): void
    {
        config(['lang-translate-api-deepl.api_key' => '']);

        $path = storage_path('framework/testing/lang/de.php');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "<?php\n\nreturn ['greeting' => 'Hallo'];\n");

        $this->artisan('translate:lang-file', ['file' => $path])
            ->assertFailed();
    }

    public function test_dry_run_does_not_write_file(): void
    {
        config(['lang-translate-api-deepl.api_key' => 'test-key']);

        $path = storage_path('framework/testing/lang/de-dry-run.php');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "<?php\n\nreturn ['greeting' => 'Hallo'];\n");

        $original = File::get($path);

        $this->artisan('translate:lang-file', [
            'file' => $path,
            '--dry-run' => true,
        ])->assertSuccessful();

        $this->assertSame($original, File::get($path));
    }
}
