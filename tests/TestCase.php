<?php

namespace Empuxa\TranslationManager\Tests;

use Empuxa\TranslationManager\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
