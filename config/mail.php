<?php
return  [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => true,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'encryption' => 'ssl',
        'host' => 'smtp.mail.ru',
        'username' => 'info@atonex.ru',
        'password' => 'MOnF5H71',
        'port' => '465',
    ],
];
