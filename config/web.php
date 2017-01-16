<?php

$common = require __DIR__ . '/common.php';

$config = [
    'components' => [
        'request' => [
            //  This is required by cookie validation
            'cookieValidationKey' => getenv('REQUEST_COOKIE_VALIDATION_KEY')? getenv('REQUEST_COOKIE_VALIDATION_KEY') : 'Xd3456dZRE'
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 7200,
            'loginUrl' => ['user/security/login'],
            'returnUrl' => ['/'],
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
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
    ],
    'modules' => [
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'cost' => 12,
            'enableRegistration' => false,
            'enableConfirmation' => false,
            'enableUnconfirmedLogin' => false,
            'adminPermission' => 'admin',
            /*'urlRules' => [
             '<id:\d+>'                               => 'profile/show',
                '<action:(register|resend)>'             => 'registration/<action>',
                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
                'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
                'settings/<action:\w+>'                  => 'settings/<action>'
            ]*/
            'mailer' => [
                'sender'                => getenv('ADMIN_EMAIL'),
            ],
        ],
    ]
];


if (file_exists(APP_BASE_PATH . '/config/web.php')) {
    // Local configuration, if available
    $local = require APP_BASE_PATH . '/config/web.php';
    $config = \yii\helpers\ArrayHelper::merge($config, $local);
}


return \yii\helpers\ArrayHelper::merge($common, $config);
