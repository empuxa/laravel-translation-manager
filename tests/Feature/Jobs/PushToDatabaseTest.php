<?php

namespace Empuxa\TranslationManager\Tests\Feature\Jobs;

use Empuxa\TranslationManager\Jobs\PushToDatabase;
use Empuxa\TranslationManager\Models\Translation;
use Empuxa\TranslationManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class PushToDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public const FILE = __DIR__ . '/../../../resources/lang/default/folder_for_testing/testing.php';

    public function test_without_existing_translations(): void
    {
        $this->assertSame(0, Translation::count());

        PushToDatabase::dispatchSync(
            'folder_for_testing.testing',
            File::getRequire(self::FILE),
        );

        $this->assertGreaterThan(0, Translation::count());
    }

    public function test_force_push(): void
    {
        Translation::create([
            'file'        => 'folder_for_testing.testing',
            'name'        => 'folder_for_testing',
            'group_key'   => 'folder-for-testing-testing-description',
            'locale'      => 'default',
            'translation' => 'string_to_be_overwritten',
        ]);

        $this->assertSame(1, Translation::count());

        $translation = Translation::first();

        $this->assertSame('string_to_be_overwritten', $translation->translation);

        PushToDatabase::dispatchSync(
            'folder_for_testing.testing',
            File::getRequire(self::FILE),
            true,
        );

        $this->assertSame('This file is for self-testing purposes only.', $translation->fresh()->translation);
    }
}
