<?php

$common = require __DIR__ . '/common.php';

$config = [
    'controllerNamespace' => 'colibri\commands',
    'components' => [
        
    ],
];

return \yii\helpers\ArrayHelper::merge($common, $config);
