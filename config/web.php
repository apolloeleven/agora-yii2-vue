<?php

use yii\rest\UrlRule;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Agora',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@portalUrl' => env('PORTAL_HOST'),
        '@storage' => dirname(__DIR__) . '/web/storage',
        '@storageUrl' => env('API_HOST') . '/storage',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => yii\web\User::class,
            'identityClass' => \app\modules\v1\setup\resources\UserResource::class,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'v1/setup/site/error',
        ],
        'mailer' => [
            'class' => \intermundia\mailer\SwiftMailer::class,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => env('SMTP_HOST'),
                'username' => env('SMTP_USERNAME'),
                'password' => env('SMTP_PASSWORD'),
                'port' => env('SMTP_PORT'),
                'encryption' => env('SMTP_ENCRYPTION'),
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/setup/country'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/setup/department'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/users/user'],
                [
                    'class' => UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        'v1/users/invitation',
                        'v1/users/employee',
                        'v1/users/auth',
                        'v1/workspaces/workspace',
                        'v1/workspaces/article',
                    ]
                ]
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => \app\modules\v1\V1Module::class,
            'modules' => [
                'setup' => [
                    'class' => \app\modules\v1\setup\SetupModule::class
                ],
                'users' => [
                    'class' => \app\modules\v1\users\UserModule::class
                ],
                'workspaces' => [
                    'class' => \app\modules\v1\workspaces\WorkspaceModule::class
                ]
            ]
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', 'localhost', '172.*', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', 'localhost', '172.*', '::1'],
    ];
}

return $config;
