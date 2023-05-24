<?php

namespace Empuxa\TranslationManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PushToStorage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Push all local files to storage.
     */
    public function handle(): void
    {
        collect(File::allFiles(lang_path()))->each(static function ($file): void {
            Storage::disk(config('translation-manager.storage.disk'))
                ->put(
                    sprintf('%s/%s', config('translation-manager.storage.path'), $file->getRelativePathname()),
                    $file->getContents(),
                );
        });
    }
}
