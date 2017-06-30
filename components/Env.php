<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\components;

/**
 * Colibri Environment class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class Env extends \yii\base\Component
{
    /**
     * Load file containing environment variables and setup
     * environment with these variables
     *
     * @param string $file
     *
     * @return void
     */
    public static function load($file)
    {
        if (file_exists($file)) {
            $env = \M1\Env\Parser::parse(file_get_contents($file));
            foreach ($env as $key => $value) {
                putenv("{$key}={$value}");
            }
        }
    }
}
