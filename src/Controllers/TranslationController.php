<?php

namespace Empuxa\TranslationManager\Controllers;

use Empuxa\TranslationManager\Jobs\PullFromDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TranslationController extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $model = config('translation-manager.model');

        return view('translation-manager::index', [
            'locales'      => $model::getLocales(),
            'translations' => $model::query()
                ->orderBy('file')
                ->orderBy('order')
                ->get()
                ->groupBy('group_key'),
        ]);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request): RedirectResponse
    {
        $model = config('translation-manager.model');

        foreach ($request->all() as $key => $values) {
            if (! is_array($values) || Str::startsWith($key, '_')) {
                continue;
            }

            $reference = $model::where('group_key', $key)
                ->where('locale', config('translation-manager.locale.default'))
                ->first();

            foreach ($values as $lang => $value) {
                $model::updateOrCreate([
                    'name'      => $reference->name,
                    'file'      => $reference->file,
                    'group_key' => $reference->group_key,
                    'locale'    => $lang,
                ], [
                    'translation' => $value ?? '',
                ]);
            }
        }

        $this->updateLocaleFiles();

        session()->flash('message', 'Translations successfully updated.');

        return redirect(route('translation-manager.index'));
    }

    protected function updateLocaleFiles(): void
    {
        foreach (config('translation-manager.output') as $locale) {
            if ($locale === config('translation-manager.locale.default')) {
                continue;
            }

            PullFromDatabase::dispatchSync($locale);
        }
    }
}
