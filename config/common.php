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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'tablePrefix' => getenv('DB_TABLE_PREFIX'),
            'enableSchemaCache' => YII_ENV_PROD ? true : false,
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
    'modules' => [],
];


return $common;
