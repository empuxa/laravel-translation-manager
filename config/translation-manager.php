<?php

return [
    'route'   => [
        /**
         * The prefix for routes.
         * Default: 'translation-manager'
         */
        'path'       => 'translation-manager',

        /**
         * The middlewares to use.
         * Default: ['web']
         */
        'middleware' => ['web'],
    ],

    /**
     * The model to use for the translations.
     * Default: Empuxa\TranslationManager\Models\Translation::class
     */
    'model'   => Empuxa\TranslationManager\Models\Translation::class,

    /**
     * The table name.
     * You might need to manually update the model as well, if you change this.
     * Default: 'translations'
     */
    'table'   => 'translations',

    /**
     * The storage to use for the translations.
     *
     * This is only relevant if you intend to handle translations in a multi-server environment.
     * If you only have one server, you can ignore these settings.
     *
     * When you push files to the storage, the `lang` folder is being copied to the given path within the storage.
     * When the files are being pulled from the storage, the files are being copied to a temp folder within the
     * local storage before we copy the contents to the `lang` folder. The temp folder will be deleted afterward.
     */
    'storage' => [
        /**
         * The disk to use for the translations.
         * Default: 'local'
         */
        'disk' => env('FILESYSTEM_DISK', 'local'),

        /**
         * The path to use for the translations.
         * When translations are being pulled from the storage, we'll create a temporary folder in the storage.
         * Default: 'translations'
         */
        'path' => 'translations',
    ],

    /**
     * The locales to use for the translations.
     *
     * In this example, we have three locale files within the `lang` folder: `default`, `de_DE` and `en_DE`.
     * The `default` locale cannot be changed via the web interface.
     * It's also the only locale file, that developers should touch.
     */
    'locale'  => [
        /**
         * The default locale to use for the translations, which can not be changed via the web interface.
         * Default: 'default'
         */
        'default' => 'default',

        /**
         * The locales that you want to include in your app.
         * Default: ['de_DE', 'en_DE']
         */
        'output'  => [
            'de_DE',
            'en_DE',
        ],
    ],

    'ui' => [
        /**
         * The character limit for the input fields.
         * If the limit is reached, a textarea will be used.
         * Default: 100
         */
        'input-limit'      => 100,

        /**
         * The URL to the Tailwind CDN.
         *
         * You might want to override the template to use your own Tailwind version.
         * Default: 'https://cdn.tailwindcss.com'
         */
        'tailwind-cdn-url' => 'https://cdn.tailwindcss.com',
    ],
];
