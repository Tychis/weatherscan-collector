<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HERE.com API
    |--------------------------------------------------------------------------
    |
    | This is an optional service. If HERE.com is enabled in the environment
    | variables, and an API key is provided, the map will be exposed to the
    | front-end interface and additional features are enabled.
    |
    */

    'here' => [
        'ENABLE_HERE' => env('ENABLE_HERE', 'false'),
        'HERE_API_KEY' => env('HERE_API_KEY', null),
    ],

];
