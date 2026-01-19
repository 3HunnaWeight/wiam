<?php

return [
    'id' => 'wiam-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [],
    'controllerNamespace' => 'yii\\console\\controllers',
    'components' => [
        'db' => require __DIR__ . '/db.php',
    ],
];
