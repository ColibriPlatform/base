<?php

defined('APP_BASE_PATH') or die('APP_BASE_PATH constant must be defined.');

$common = [
    'id' => 'colibri',
    'name' => getenv('APP_NAME') ? getenv('APP_NAME') : 'Colibri',
    'timeZone' => getenv('APP_TIMEZONE')? getenv('APP_TIMEZONE') : 'UTC',
    'language' => getenv('APP_LANGUAGE')? getenv('APP_LANGUAGE') : 'en',
    'basePath' => APP_BASE_PATH,
    'viewPath' => realpath(__DIR__ . '/../views'),
    'bootstrap' => ['log'],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'enableSchemaCache' => YII_ENV_PROD ? true : false,
        ],
        'assetManager' => [
            'linkAssets' => YII_ENV_DEV ? true : false,
        ],

        /*'authManager' => [
         'class' => 'yii\rbac\DbManager',
        ],*/

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'i18n' => [
            'translations' => [
                // Default category translation
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    APP_BASE_PATH . '/messages'
                ],
                'colibri' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/messages'
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
    ],
];

if (getenv('APP_CONFIG_FILE') && file_exists(APP_BASE_PATH . '/' . getenv('APP_CONFIG_FILE'))) {
    // Local configuration, if available
    $local = require APP_BASE_PATH . '/' . getenv('APP_CONFIG_FILE');
    $common = \yii\helpers\ArrayHelper::merge($common, $local);
}

return $common;
