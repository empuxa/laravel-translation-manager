<?php

namespace Empuxa\TranslationManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Translation extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $guarded = [
        'id',
    ];

    public function getPathAttribute(): string
    {
        return sprintf('%s.%s', Str::replace('.', '/', $this->file), $this->name);
    }

    public static function getLocales(): array
    {
        $locales = Config::get('translation-manager.locale.output');

        if (! Arr::has($locales, Config::get('translation-manager.locale.default'))) {
            array_unshift($locales, Config::get('translation-manager.locale.default'));
        }

        return $locales;
    }

    public static function getGroupKey(string $file, string $name): string
    {
        $key = $file . '-' . $name;

        return str_replace(['.', '_'], '-', $key);
    }

    public static function getPathKey(string $file, string $name): string
    {
        return sprintf('%s.%s', Str::replace('.', '/', $file), $name);
    }
}
