<?php

return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=cms',
            'username' => 'root',
            'password' => 'password',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/basic/views',
                    '@app/modules' => '@app/themes/basic/modules',
                    '@app/mail' => '@app/themes/basic/mail',
                ],
            ],
        ],
    ],
];