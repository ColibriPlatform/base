<?php

$common = require __DIR__ . '/common.php';

$config = [
    'components' => [
        'request' => [
            //  This is required by cookie validation
            'cookieValidationKey' => getenv('REQUEST_COOKIE_VALIDATION_KEY')? getenv('REQUEST_COOKIE_VALIDATION_KEY') : uniqid()
        ],
        'user' => [
            'identityClass' => 'colibri\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 7200,
            'loginUrl' => ['default/login']
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
];

return \yii\helpers\ArrayHelper::merge($common, $config);
