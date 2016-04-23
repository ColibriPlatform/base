<?php

defined('APP_BASE_PATH') or die('APP_BASE_PATH constant must be defined.');

$common = [
    'id' => 'colibri',
    'name' => getenv('APP_NAME') ? getenv('APP_NAME') : 'Colibri',
    'timeZone' => getenv('APP_TIMEZONE')? getenv('APP_TIMEZONE') : 'UTC',
    'language' => getenv('APP_LANGUAGE')? getenv('APP_LANGUAGE') : 'en-US',
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
            // Default category translation
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => dirname(__DIR__) . '/src/messages'
                ],
            ],
        ],
    ],
    'modules' => [],
];

if (getenv('APP_CONFIG_FILE') && file_exists(APP_BASE_PATH . '/' . getenv('APP_CONFIG_FILE'))) {
    // Local configuration, if available
    $local = require APP_BASE_PATH . '/' . getenv('APP_CONFIG_FILE');
    $common = \yii\helpers\ArrayHelper::merge($common, $local);
}

return $common;
