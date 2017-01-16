<?php

$common = require __DIR__ . '/common.php';

$config = [
    'controllerNamespace' => 'colibri\commands',
    'components' => [

    ],
];

if (file_exists(APP_BASE_PATH . '/config/console.php')) {
    // Local configuration, if available
    $local = require APP_BASE_PATH . '/config/console.php';
    $config = \yii\helpers\ArrayHelper::merge($config, $local);
}

return \yii\helpers\ArrayHelper::merge($common, $config);
