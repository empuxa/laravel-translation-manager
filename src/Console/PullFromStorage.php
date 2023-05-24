<?php

namespace Empuxa\TranslationManager\Console;

use Empuxa\TranslationManager\Jobs\PullFromStorage as PullFromStorageJob;
use Illuminate\Console\Command;

class PullFromStorage extends Command
{
    /**
     * @var string
     */
    protected $signature = 'translation-manager:pull-from-storage';

    /**
     * @var string
     */
    protected $description = 'Get files from storage and push them to the local lang folder.';

    public function handle(): int
    {
        PullFromStorageJob::dispatchSync();

        return self::SUCCESS;
    }
}
