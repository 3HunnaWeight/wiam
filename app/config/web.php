<?php

$params = require __DIR__ . '/params.php';

return [
    'id' => 'wiam',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'wiam',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'POST requests' => 'requests/create',
                'GET processor' => 'processor/process',
            ],
        ],
        'db' => require __DIR__ . '/db.php',
        'errorHandler' => [
            'errorAction' => null,
        ],
    ],
    'params' => $params,
];
