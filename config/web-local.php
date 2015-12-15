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
        'reCaptcha' => [
            // Ключи нужно регистрировать https://www.google.com/recaptcha/intro/index.html
            'siteKey' => '6LcEIBMTAAAAAIzOq9EkEvxVxr5zXXvqwC7pWklZ',
            'secret' => '6LcEIBMTAAAAALjDjQzdRlWWVVTlmR8xuGP8e8kz',
        ],
    ],
];