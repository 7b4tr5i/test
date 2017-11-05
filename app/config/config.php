<?php
return [
    'db' => require_once(app . 'app/config/db.php'),
    'routes' => app . 'Router',
    'components' => [
        'Test' => \app\components\mailer\TestComponent::class
    ],
    'exceptions' => [
        \base\exceptions\RestException::class
    ]
];