<?php
use M1\Env\Parser;

if (file_exists(__DIR__.'/../../.env')) {
    $env = Parser::parse(file_get_contents(__DIR__.'/../../.env'));
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
}

defined('YII_DEBUG') or define('YII_DEBUG', (boolean) getenv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV'));
