<?php

$config = [
    'id' => 'app',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'f32jfRF#HJzh43wfhw',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'bootstrap' => ['post', 'user'],
    'modules' => [
        'post' => 'app\modules\post\Module',
        'user' => 'app\modules\user\Module',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;