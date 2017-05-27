<?php
namespace colibri\base;
use yii\helpers\ArrayHelper;

// Load environment config
require __DIR__ . '/config/env.php';

// Then load Yii framework
require APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';

/**
 * Colibri base class
 *
 * @author Sylvain Philip <contact@sphilip.com>
 */
class Colibri
{
    public static function run(array $localConfig = [])
    {
        // Merge default config with local config
        $defaultConfig = require __DIR__ . '/config/web.php';
        $config = ArrayHelper::merge($defaultConfig, $localConfig);

        // Then launch Colibri application
        (new Application($config))->run();
    }
}
