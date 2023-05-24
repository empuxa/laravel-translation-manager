<?php

namespace Empuxa\TranslationManager\Console;

use Empuxa\TranslationManager\Jobs\PullFromDatabase as PullFromDatabaseJob;
use Illuminate\Console\Command;

class PullFromDatabase extends Command
{
    /**
     * @var string
     */
    protected $signature = 'translation-manager:pull-from-db {locale=all}';

    /**
     * @var string
     */
    protected $description = 'Pull translations from database and write them to "locale" files.';

    public function handle(): int
    {
        if ($this->argument('locale') === 'all') {
            foreach (config('translation-manager.locale.output') as $locale) {
                PullFromDatabaseJob::dispatchSync($locale);
            }
        } else {
            PullFromDatabaseJob::dispatchSync($this->argument('locale'));
        }

        return self::SUCCESS;
    }
}
