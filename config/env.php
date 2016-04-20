<?php
use M1\Env\Parser;

defined('APP_BASE_PATH') or die('APP_BASE_PATH constant must be defined.');

if (file_exists(APP_BASE_PATH.'/.env')) {
    $env = Parser::parse(file_get_contents(APP_BASE_PATH.'/.env'));
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
}

defined('YII_DEBUG') or define('YII_DEBUG', (boolean) getenv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV')? getenv('YII_ENV') : 'prod');
