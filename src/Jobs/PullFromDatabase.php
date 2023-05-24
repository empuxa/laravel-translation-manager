<?php

namespace Empuxa\TranslationManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PullFromDatabase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $locale)
    {
    }

    public function handle(): void
    {
        $model = config('translation-manager.model');

        $translations = $model::where('locale', $this->locale)->get();

        if ($translations->count() === 0) {
            return;
        }

        $localeBasePath = lang_path($this->locale);
        $localeFiles = $translations->pluck('file', 'file');

        self::createDirIfRequired($localeBasePath);

        foreach ($localeFiles as $localeFile) {
            $content = $translations
                ->where('file', $localeFile)
                ->pluck('translation', 'name')
                ->toArray();

            $path = $localeBasePath;

            // This file has folders.
            // Attention: this can also be a domain name in the path!
            if (Str::contains($localeFile, '.')) {
                $localePath = $localeBasePath . '/' . Str::replace('.', '/', Str::beforeLast($localeFile, '.'));

                self::createDirIfRequired(Str::beforeLast($localeFile, '.'), $localeBasePath);

                $localeFile = Str::afterLast($localeFile, '.');

                $path = $localePath;
            }

            $output = "<?php\n\nreturn " . var_export(self::getTranslationsAsArray($content), true) . ';' . \PHP_EOL;

            File::put(sprintf('%s/%s.php', $path, $localeFile), $output);

            unset($localePath);
        }
    }

    public static function createDirIfRequired(string $folders, string $path = ''): void
    {
        $subFolders = explode('.', $folders);

        // Main folder. Language folders don't use slashes but dots instead.
        if (Str::is('*/*', $folders)) {
            $subFolders = [$folders];
        }

        // Sub-folder
        foreach ($subFolders as $folder) {
            $fullPath = $path !== '' ? $path . '/' . $folder : $folder;

            if (! File::isDirectory($fullPath)) {
                File::makeDirectory($fullPath);
            }

            $path = $fullPath;
        }
    }

    public static function getTranslationsAsArray(array $content): array
    {
        $array = [];

        foreach ($content as $key => $value) {
            data_set($array, $key, $value);
        }

        return $array;
    }
}
