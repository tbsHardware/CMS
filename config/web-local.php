<?php

return [
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=cms',
            'username' => 'root',
            'password' => 'password',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/basic/views',
                    '@app/modules' => '@app/themes/basic/modules'
                ],
            ],
        ],
    ],
];