<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '0Hs6EBUJD0t29lDG2H7EPF0Y1J6GFS3E',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
                [
                'class' => 'yii\rest\UrlRule',
                'controller' => 'v1/auth',
                'pluralize' => false,
                'extraPatterns' => [
                    'POST login' => 'login', 
                    'POST registar' => 'registar', 
                ],
            ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/user','v1/pedido'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET pedido' => 'index', // 'update' é 'actionupdate'
                        'POST pedido' => 'criar', // 'update' é 'actionCriar'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/produto'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET categoria/{id}' => 'categoria', // 'update' é 'actionupdate'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/pedidoproduto',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET all/{id}' => 'all', // 'update' é 'actionupdate'
                        'POST criar' => 'criar', // 'update' é 'actionupdate'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/perfil',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET {id}/perfil' => 'user', // 'morada' é 'actionMorada'
                        'PUT {id}/update' => 'update', // 'update' é 'actionupdate'
                        'POST criar'=>'criar',
                    ],
                ],

            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
