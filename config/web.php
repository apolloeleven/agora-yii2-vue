<?php

use yii\rest\UrlRule;

$config = \yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common.php',
    [
        'id' => 'basic',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log'],
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'enableCookieValidation' => false,
                'parsers' => [
                    'application/json' => \yii\web\JsonParser::class
                ]
            ],
            'user' => [
                'class' => yii\web\User::class,
                'identityClass' => \app\modules\v1\setup\resources\UserProfileResource::class,
                'enableSession' => false,
                'loginUrl' => null,
            ],
            'errorHandler' => [
                'errorAction' => 'v1/setup/site/error',
            ],

            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                    [
                        'pattern' => 'v1/workspaces/folder/download-file/<id:\d+>',
                        'route' => 'v1/workspaces/folder/download-file'
                    ],
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
                            'v1/workspaces/timeline',
                            'v1/workspaces/article-file',
                            'v1/workspaces/user-comment',
                            'v1/workspaces/user-like',
                            'v1/workspaces/folder',
                            'v1/workspaces/workspace-activity'
                        ]
                    ],
                ],
            ],
            'authManager' => [
                'class' => 'yii\rbac\DbManager',
            ],
            'async' => [
                'class' => \app\components\AsyncComponent::class
            ]
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
    ]
);

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
