<?php
/**
 * This file is part of the Pandawa package.
 *
 * (c) 2019 Pandawa <https://github.com/pandawa>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Publisher
    |--------------------------------------------------------------------------
    |
    | This option controls the default publisher connection that gets used while
    | using Bima client. This connection is used when syncing all models
    | to the bima server. You should adjust this based on your needs.
    |
    | Supported: "http"
    |
    */

    'driver' => env('BIMA_DRIVER', 'http'),

    /*
    |--------------------------------------------------------------------------
    | Registered tables to be publishes
    |--------------------------------------------------------------------------
    |
    | These options allow you to control the tables that should be published.
    | Do not use prefix if you use prefix on table.
    |
    */

    'tables' => ['users', 'schemas'],

    /*
    |--------------------------------------------------------------------------
    | Project ID Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Bima project id.
    |
    */

    'project_id' => env('BIMA_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Auth Token
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Bima auth token.
    |
    */

    'token' => env('BIMA_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Queue Data Publishing
    |--------------------------------------------------------------------------
    |
    | This option allows you to control if the operations that publish your data
    | with your publisher server are queued. When this is set to "true" then
    | all automatic data publishing will get queued for better performance.
    |
    */

    'queue' => [
        'queue'      => env('BIMA_QUEUE', false),
        'connection' => env('BIMA_QUEUE_CONNECTION'),
    ],

    'drivers' => [
        'http' => [
            'endpoint' => env('BIMA_HTTP_ENDPOINT', 'http://localhost:8000/api/v1/'),
            'timeout'  => env('BIMA_HTTP_TIMEOUT', 60 * 5),
        ],
    ],
];

