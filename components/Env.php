<?php
namespace colibri\base\components;

class Env extends \yii\base\Component
{

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