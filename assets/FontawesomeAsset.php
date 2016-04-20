<?php

namespace colibri\base\assets;

/**
 * FontawesomeAsset
 * 
 */
class FontawesomeAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/fontawesome';

    public $css = [
        'css/font-awesome.min.css'
    ];

    public $depends = [

    ];

}