<?php

namespace Empuxa\TranslationManager\Tests\Feature\Jobs;

use Empuxa\TranslationManager\Jobs\PullFromDatabase;
use Empuxa\TranslationManager\Models\Translation;
use Empuxa\TranslationManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PullFromDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_without_translations(): void
    {
        $file = 'en_DE/folder_for_testing/testing.php';

        $this->fileCleanup($file);

        PullFromDatabase::dispatchSync('en_DE');

        $this->assertFileDoesNotExist(lang_path($file));
    }

    protected function fileTester(string $file = 'en_DE/folder_for_testing/testing.php', array $data = []): void
    {
        $data = array_merge([
            'file'        => 'folder_for_testing.testing',
            'name'        => 'description',
            'group_key'   => 'folder-for-testing-testing-description',
            'locale'      => 'en_DE',
            'translation' => 'test',
        ], $data);

        Translation::create($data);

        PullFromDatabase::dispatchSync('en_DE');

        $this->assertFileExists(lang_path($file));

        $this->assertStringContainsString(
            "'description' => 'test'",
            file_get_contents(lang_path($file))
        );
    }

    protected function fileCleanup(string $file = 'en_DE/test.php'): void
    {
        if (file_exists(lang_path($file))) {
            unlink(lang_path($file));
        }

        $this->assertFileDoesNotExist(lang_path($file));
    }

    public function test_handle_with_translations(): void
    {
        $this->fileCleanup();

        $this->fileTester();
    }
}
