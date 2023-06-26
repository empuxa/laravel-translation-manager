<?php

namespace Empuxa\TranslationManager;

use Empuxa\TranslationManager\Console\PullFromDatabase;
use Empuxa\TranslationManager\Console\PullFromStorage;
use Empuxa\TranslationManager\Console\PushToDatabase;
use Empuxa\TranslationManager\Console\PushToStorage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TranslationManagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('translation-manager')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('create_translations_table')
            ->hasRoute('web')
            ->hasCommands([
                PushToStorage::class,
                PushToDatabase::class,
                PullFromDatabase::class,
                PullFromStorage::class,
            ]);
    }
}
