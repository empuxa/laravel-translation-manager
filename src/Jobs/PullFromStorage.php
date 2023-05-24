<?php

namespace Empuxa\TranslationManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PullFromStorage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const STORAGE_PREFIX = 'temp-pulled-';

    public function handle(): void
    {
        self::downloadFromStorage();

        self::moveToLang();

        self::removeTempFolder();
    }

    /**
     * Download all files from storage to local storage.
     * This is needed because we can't copy files directly from storage to lang_path().
     * Also, we save the files with a prefix to avoid conflicts with existing files.
     */
    public static function downloadFromStorage(): void
    {
        $files = Storage::disk(config('translation-manager.storage.disk'))
            ->allFiles(config('translation-manager.storage.path'));

        collect($files)
            ->each(static function ($file): void {
                Storage::disk('local')->put(
                    self::STORAGE_PREFIX . $file,
                    Storage::disk(config('translation-manager.storage.disk'))->get($file),
                );
            });
    }

    public static function moveToLang(): void
    {
        File::copyDirectory(
            Storage::disk('local')->path(self::STORAGE_PREFIX . config('translation-manager.storage.path')),
            lang_path()
        );
    }

    public static function removeTempFolder(): void
    {
        Storage::disk('local')->deleteDirectory(self::STORAGE_PREFIX . config('translation-manager.storage.path'));
    }
}
