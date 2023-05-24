<?php

namespace Empuxa\TranslationManager\Tests\Unit;

use Empuxa\TranslationManager\Jobs\PullFromDatabase;
use Orchestra\Testbench\TestCase;

class PullFromDatabaseTest extends TestCase
{
    public function test_create_dir_if_required(): void
    {
        $this->assertFalse(is_dir('../lang'));

        PullFromDatabase::createDirIfRequired('../lang');

        $this->assertTrue(is_dir('../lang'));

        // Cleanup this test
        rmdir('../lang');
    }
}
