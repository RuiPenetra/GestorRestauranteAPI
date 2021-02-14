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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
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
                    'controller' => ['v1/noauth'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST registaruser' => 'registaruser',
                        'GET produto' => 'todosprodutos',
                        'GET produtocategoria/{id}' => 'produtocategoria',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/pedido'],
                    'pluralize' => false,
                    'except' => ['create','update','delete'],
                    'extraPatterns' => [
                        'GET pedido' => 'index',                    // 'update' é 'actionupdate'
                        'POST restaurante' => 'pedrestaurante',     // 'actionCriar' [ criar pedido produto ]
                        'POST takeaway' => 'pedtakeaway',
                        'DELETE {id}' => 'apagar',                  // 'update' é 'actionCriar'
                    ],
                ],
                //PEDIDO PRODUTO
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/produto'],
                    'pluralize' => false,
                    'except' => ['create','update','delete'],
                    'extraPatterns' => [
                        'GET categoria/{id}' => 'categoria', // 'actionCategoria' [ mostrar todos os produtos determinada categoria ]
                        'POST teste' => 'teste', // 'actionTeste' [ mostrar todos os produtos determinada categoria ]
                    ],
                ],

                //CATEGORIA
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/categoriaProduto'],
                    'pluralize' => false,
                    'except' => ['index','create','update','delete'],
                    'extraPatterns' => [
                        'GET categoria/{id}' => 'categoria', // 'update' é 'actionupdate'
                    ],
                ],
                //PEDIDO PRODUTO
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/pedidoproduto',
                    'pluralize' => false,
                    'except' => ['create','update','delete'],
                    'extraPatterns' => [
                        'GET {id}' => 'index',      // 'actionIndex'  [ mostrar todos os pedidos produto de um pedido ]
                        'POST restaurante' => 'addrestaurante',          // 'actionCriar' [ criar pedido produto ]
                        'POST takeaway' => 'addtakeaway',          // 'actioncriarTakeaway' [ adicionar pedido produto ao pedido takeaway ]
                        'PUT {id}' => 'atualizar',   // 'actionAtualizar' [ atualizar pedido produto ]
                        'DELETE {id}' => 'remover',  // 'actionApagar' [ apagar pedido produto ]
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/perfil',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET ' => 'index', // 'morada' é 'actionMorada'
                        'GET todos' => 'todos', // 'morada' é 'actionMorada'
                        'PUT {id}' => 'atualizar', // 'update' é 'actionupdate'
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
