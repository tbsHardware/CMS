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
    ],
];