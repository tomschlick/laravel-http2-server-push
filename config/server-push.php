<?php

return [

    // Include assets you want to link on every page load here.
    'default_links' => [
        'styles' => [

        ],

        'scripts' => [

        ],

        'images' => [

        ],
    ],

    // Auto link all files from your built manifest
    'autolink_from_manifest' => true,

    // Elixir example
    'manifest_path'   => public_path('build/rev-manifest.json'),
    'assets_base_uri' => '/build/',

    // Mix example
    //'manifest_path'   => public_path('mix-manifest.json'),
    //'assets_base_uri' => '/',
];
