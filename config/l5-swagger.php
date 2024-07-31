<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'My API Documentation',
                'version' => '1.0.0',
            ],
            'routes' => [
                'api' => 'api/documentation',
                'docs' => 'docs',
            ],
            'paths' => [
                'annotations' => base_path('app/Swagger'),
            ],
        ],
    ],

    'paths' => [
        'annotations' => base_path('app/Swagger'),
        'docs' => storage_path('api-docs'),
        'assets' => public_path('vendor/swagger-ui'),
        'views' => resource_path('views/vendor/swagger-ui'),
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'excludes' => [],
        'includes' => [],
    ],

    'security' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
            'description' => 'Enter your token in the format: Bearer {token}',
        ],
    ],

    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
    'swagger_version' => env('L5_SWAGGER_SWAGGER_VERSION', '3.0'),
    'proxy' => env('L5_SWAGGER_PROXY', false),
    'headers' => [
        'view' => [],
        'json' => [],
    ],
    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
    ],
];
