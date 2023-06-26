<?php

namespace Empuxa\TranslationManager\Console;

use Empuxa\TranslationManager\Jobs\PushToDatabase as PushToDatabaseJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PushToDatabase extends Command
{
    /**
     * @var string
     */
    protected $signature = 'translation-manager:push-to-db {--force}';

    /**
     * @var string
     */
    protected $description = 'Read local translations and push them to the database.';

    public function handle(): int
    {
        $locale = config('translation-manager.locale.default');

        $files = File::allFiles(lang_path($locale));

        $bar = $this->output->createProgressBar(count($files));

        foreach ($files as $file) {
            $fileName = Str::before($file->getFilename(), '.');

            // No support for JSON files right now
            if ($file->getExtension() !== 'php') {
                continue;
            }

            // This is a directory
            if (! Str::is($locale, $file->getRelativePath())) {
                $fileName = Str::replace('/', '.', Str::before($file->getRelativePathname(), '.'));
                $fileName = Str::replace($locale . '.', '', $fileName);
            }

            PushToDatabaseJob::dispatchSync($fileName, File::getRequire($file), $this->option('force'));

            $bar->advance();
        }

        $bar->finish();

        return self::SUCCESS;
    }
}
