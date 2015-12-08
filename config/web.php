<?php

$config = [
    'id' => 'app',
    'language' => 'ru-RU',
    'bootstrap' => ['post', 'users'],
    'modules' => [
        'post'  => 'app\modules\post\Module',
        'users'  => 'app\modules\users\Module',
        'admin'  => 'app\modules\admin\Module',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'f32jfRF#HJzh43wfhw',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        /*'view' => [
            'theme' => [
                'basePath' => '@app/themes/default',
                'baseUrl' => '@app/themes/default',
                'pathMap' => [
                    '@app/views' => '@app/themes/default',
                    '@app/modules' => '@app/themes/default/modules',
                    '@app/widgets' => '@app/themes/default/widgets',
                ],
            ],
        ],*/
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