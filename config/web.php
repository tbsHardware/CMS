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
            'cookieValidationKey' => 'f32jfRF#HJzh43wfhw',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
        ],
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