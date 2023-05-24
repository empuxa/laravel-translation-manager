<?php

namespace Empuxa\TranslationManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * This class will push any new keys from the default locale to the database and also create its pendants for
 * the other locales that are available.
 */
class PushToDatabase implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $file,
        private readonly array $contents,
        private readonly bool $force = false
    ) {
    }

    public function handle(): void
    {
        $model = config('translation-manager.model');

        $order = 1;

        foreach (Arr::dot($this->contents) as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            foreach ($model::getLocales() as $locale) {
                $translation = $model::where('locale', $locale)
                    ->where('group_key', $model::getGroupKey($this->file, $key))
                    ->first();

                $string = __($model::getPathKey($this->file, $key), [], $locale) ?: '';

                if ($string === $model::getPathKey($this->file, $key)) {
                    $string = $value;
                }

                if (is_null($translation)) {
                    $translation = new $model;

                    $translation->file = $this->file;
                    $translation->name = $key;
                    $translation->group_key = $model::getGroupKey($this->file, $key);
                    $translation->locale = $locale;
                    $translation->translation = $string;
                }

                if ($this->force) {
                    $translation->translation = $string;
                }

                if (Str::is(config('translation-manager.locale.default'), $locale)) {
                    $translation->translation = $value;
                }

                $translation->order = $order;

                $translation->save();
            }

            $order++;
        }
    }
}
