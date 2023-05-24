<?php

namespace Empuxa\TranslationManager\Console;

use Empuxa\TranslationManager\Jobs\PushToStorage as PushToCloudJob;
use Illuminate\Console\Command;

class PushToStorage extends Command
{
    /**
     * @var string
     */
    protected $signature = 'translation-manager:push-to-storage';

    /**
     * @var string
     */
    protected $description = 'Push local translations to the pre-defined storage disk.';

    public function handle(): int
    {
        PushToCloudJob::dispatchSync();

        return self::SUCCESS;
    }
}
