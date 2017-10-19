<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin route prefix
    |--------------------------------------------------------------------------
    |
    | This option determines the admin route prefix. Based on this configuration
    | route prefix will performed in admin side
    |
    */
    'encode_decode_separator' => '_time',
    'vendor' => 'contus',
    'package' => 'user',
    'setting_cache_file_path' => storage_path('app'.DIRECTORY_SEPARATOR.'sitesettings.json'),
    'translation_cache_file_path' => public_path('assets'.DIRECTORY_SEPARATOR.'locale'),
];