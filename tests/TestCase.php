<?php

namespace Empuxa\TranslationManager\Tests;

use Empuxa\TranslationManager\TranslationManagerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            TranslationManagerServiceProvider::class,
        ];
    }
}
