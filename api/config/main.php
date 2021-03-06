<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module',
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'class' => 'yii\web\Response',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'gbuz-ckksks-backend',
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
//        'errorHandler' => [
//            'errorAction' => 'site/error',
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'site',
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST /' => 'index',
                        'GET token/<refresh_token:\w+>' => 'token',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/user',
                        'v1/measuring-instrument',
                        'v1/test-equipment',
                        'v1/auxiliary-equipment',
                        'v1/standard-sample',
                        'v1/IndustrialPremise',
                        'v1/employee',
                        'v1/dictionary',
                    ],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'DELETE delete/<id:\d+>' => 'delete',
                        'GET view/<id:\d+>' => 'view',
                        'PUT update' => 'update',
                        'POST create' => 'create',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
