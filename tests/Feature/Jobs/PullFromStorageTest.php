<?php

namespace Empuxa\TranslationManager\Tests\Feature\Jobs;

use Empuxa\TranslationManager\Jobs\PullFromStorage;
use Empuxa\TranslationManager\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class PullFromStorageTest extends TestCase
{
    public function test_download_from_storage(): void
    {
        $this->markTestIncomplete();
    }

    public function test_move_to_lang(): void
    {
        $fileName = microtime() . '_testfile';
        $tempStorage = PullFromStorage::STORAGE_PREFIX . config('translation-manager.storage.path');
        $fullPath = Storage::disk('local')->path($tempStorage . '/' . $fileName);

        $this->assertFileDoesNotExist(lang_path($fileName));
        $this->assertFileDoesNotExist($fullPath);

        Storage::disk('local')->makeDirectory($tempStorage);
        Storage::disk('local')->put($tempStorage . '/' . $fileName, 'test');

        $this->assertFileExists($fullPath);

        PullFromStorage::moveToLang();

        $this->assertFileExists(lang_path($fileName));
    }

    public function test_remove_from_temp(): void
    {
        $name = PullFromStorage::STORAGE_PREFIX . config('translation-manager.storage.path');

        Storage::fake('local');

        $this->assertDirectoryDoesNotExist(Storage::disk('local')->path($name));

        Storage::disk('local')->makeDirectory($name);

        $this->assertDirectoryExists(Storage::disk('local')->path($name));

        PullFromStorage::removeTempFolder();

        $this->assertDirectoryDoesNotExist(Storage::disk('local')->path($name));
    }
}
