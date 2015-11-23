<?php

$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
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
    'params' => $params,
];
