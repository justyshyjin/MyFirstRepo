<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Various Image Configuration by model
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many model based configuration such as supported_format
    | maximum_file_size in MB,temporary_image_storage_path,image_resolution,thumb_image_resolution,default_image_path
    |
    */

    'thumbnail' => [
        'supported_format'             => 'jpg,png,jpeg',
        'fileSize'                     => 2,
        'temporary_image_storage_path' => 'app/tempimage/thumbnails',
        'storage_path'                 => 'uploads/thumbnails',
        'image_resolution'             => '400x300',
    ],
    'profile' => [
        'supported_format'             => 'jpg,png,jpeg',
        'fileSize'                     => 2,
        'temporary_image_storage_path' => 'app/tempimage/profile',
        'storage_path'                 => 'uploads/profile',
        'image_resolution'             => '400x300',
    ],
    'category_image' => [
        'supported_format'             => 'jpg,png,jpeg',
        'fileSize'                     => 2,
        'temporary_image_storage_path' => 'app/tempimage/category_images',
        'storage_path'                 => 'uploads/category_images',
        'image_resolution'             => '400x300',
    ],
    'subtitle' => [
        'supported_format'             => 'mpga,mp3,audio/mp3,audio/mpeg,mpeg,mp2,mp2a,m2a,m3a,bin',
        'fileSize'                     => 1000,
        'temporary_storage_path'       => 'app/tempimage/subtitle',
        'storage_path'                 => 'contus/mp3',
        'is_file'                      => 1,
    ],
    'posters' => [
            'supported_format'             => 'jpg,png,jpeg',
            'fileSize'                     => 2,
            'temporary_image_storage_path' => 'app/tempimage/posters',
            'storage_path'                 => 'uploads/posters',
            'image_resolution'             => '400x300',
    ],
    'cast_images' => [
            'supported_format'             => 'jpg,png,jpeg',
            'fileSize'                     => 2,
            'temporary_image_storage_path' => 'app/tempimage/cast_images',
            'storage_path'                 => 'uploads/cast_images',
            'image_resolution'             => '400x300',
    ],
    'static_banner' => [
            'supported_format'             => 'jpg,png,jpeg',
            'fileSize'                     => 2,
            'temporary_image_storage_path' => 'app/tempimage/static_banner',
            'storage_path'                 => 'uploads/static_banner',
            'image_resolution'             => '1058x310',
    ],
];
