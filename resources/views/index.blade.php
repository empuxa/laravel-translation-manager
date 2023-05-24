<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    {{-- (Force latest IE rendering engine: bit.ly/1c8EiC9 --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    <script src="{{ config('translation-manager.ui.tailwind-cdn-url') }}"></script>

    <title>{{ __('translation-manager::index.headline') }}</title>
</head>
<body class="bg-gray-light dark:bg-gray-800 antialiased">
<main class="px-5 py-10 lg:p-10 w-full lg:h-auto relative flex flex-grow flex-wrap">
    <div class="flex items-center justify-between w-full mb-5 sm:mb-10 space-x-3">
        <h1 class="text-2xl md:text-3xl 2xl:text-4xl font-bold 2xl:leading-tight break-words whitespace-normal">
            {{ __('translation-manager::index.headline') }}
        </h1>
    </div>

    <div class="flex mb-5 sm:mb-10 space-x-3">
        <span class="bg-orange-100 p-1">
            {{ __('translation-manager::index.legends.matches_default') }}
        </span>
    </div>

    <form action="{{ route(config('translation-manager.route.path') . '.update') }}" method="POST" class="w-full">
        @method('PUT')
        @csrf

        <div class="flex flex-col flex-grow">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="card overflow-hidden">
                        <table class="divide-y divide-light w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="table-th px-6 py-3 w-96">
                                        {{ __('translation-manager::index.table.name') }}
                                    </th>
                                    @foreach($locales as $locale)
                                        <th class="table-th px-8 py-3 w-96">
                                            {{ $locale }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-transparent divide-y divide-light">
                                @foreach ($translations as $translation)
                                    @php
                                        $defaultModel = $translation->first();
                                        $defaultTranslation = $defaultModel->translation;
                                    @endphp

                                    @if (isset($file) && $file !== $defaultModel->file)
                                        <tr>
                                            <td class="p-6 bg-gray-50 dark:bg-gray-900 text-default uppercase font-semibold"
                                                colspan="10">
                                                {{ $defaultModel->file }}
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-default">
                                            {{ $defaultModel->path ?? '—' }}
                                        </td>

                                        @foreach($locales as $locale)
                                            @php
                                                $model = $translation->where('locale', $locale)->first();
                                                $class = $model->translation === $defaultTranslation && $locale !== config('translation-manager.locale.default') ? 'bg-orange-100' : '';
                                                $disabled = $locale === config('translation-manager.locale.default') || $translation->first()->group_key === 'enums-test-test-with-translation';
                                            @endphp

                                            <td class="px-6 py-4 whitespace-nowrap text-default {{ $class }}">
                                                @if (strlen($model->translation) > config('translation-manager.ui.input-limit', 100))
                                                    <textarea
                                                        class="block py-2 px-2 border border-gray-300 dark:border-gray-600 appearance-none focus:outline-1 focus:outline-offset-0 focus:outline-gray-500 focus:border-gray-500 dark:focus:outline-gray-700 dark:focus:border-gray-700 w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 disabled:cursor-not-allowed disabled:opacity-50 rounded-md"
                                                        name="{{ $translation->first()->group_key }}[{{ $locale }}]"
                                                        rows="{{ round(strlen($model->translation) / config('translation-manager.ui.input-limit', 100) * 2) }}"
                                                        @if ($disabled) disabled @endif
                                                    >{{ $model->translation ?? '' }}</textarea>
                                                @else
                                                    <input type="text"
                                                           class="block py-2 px-2 border border-gray-300 dark:border-gray-600 appearance-none focus:outline-1 focus:outline-offset-0 focus:outline-gray-500 focus:border-gray-500 dark:focus:outline-gray-700 dark:focus:border-gray-700 w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 disabled:cursor-not-allowed disabled:opacity-50 rounded-md"
                                                           value="{{ $model->translation ?? '' }}"
                                                           name="{{ $translation->first()->group_key }}[{{ $locale }}]"
                                                           @if ($disabled) disabled @endif
                                                    >
                                                @endif
                                                @if ($model->updated_at > $model->created_at)
                                                    <p class="text-xs mt-2 text-gray-400 ml-2">
                                                        {{ __('translation-manager::index.last_update') }}
                                                        : {{ $model->updated_at }}
                                                    </p>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>

                                    @php
                                        $file = $translation->first()->file;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed top-0 right-0 justify-center text-default p-12">
            <input type="submit" value="{{ __('translation-manager::index.cta') }}"
                   class="inline-flex items-center justify-center px-2.5 md:px-5 py-2.5 font-semibold rounded-md text-white bg-blue-500 hover:bg-blue-400 transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer focus:outline-2 focus:outline-offset-2 focus:outline-primary w-full">
        </div>
    </form>
</main>
</body>
</html>
