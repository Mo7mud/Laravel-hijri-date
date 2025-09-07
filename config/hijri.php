<?php

return [
    /**
     * List of supported locales.
     */
    'supported_locales' => ['ar', 'bn', 'dv', 'en'],

    /**
     * Locale used for formatting by default (when locale is not explicitly set).
     */
    'default_locale' => 'ar',

    /**
     * Maximum and minimum limit for Hijri date years.
     * These values are inclusive (eg: '1000-01-01' is a valid date when 'year_min' is set to 1000).
     */
    'year_max' => 1999,
    'year_min' => 1000,

    /**
     * Configuration for converting Gregorian dates to Hijri dates.
     */
    'conversion' => [
        // Customize from where the conversion mapping data is fetched, and how it is cached ...
        'data_url' => 'https://gist.githubusercontent.com/Mo7mud/181391cfc18bbaf34ad3d961d659a6f4/raw', // Umm al-Qura data for years 1343-1500 AH (1924-2077 AD)
        'cache_key' => 'hijri_to_gregorian_map',
        'cache_period' => 60 * 24,

        // ... or gain full control over how conversion works by defining your own converter class.
        'converter' => \Remls\HijriDate\Converters\MaldivesG2HConverter::class,
    ],
];
