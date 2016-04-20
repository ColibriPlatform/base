<?php

namespace colibri\base\assets;

/**
 * AdminLteAsset
 * 
 */
class AdminlteAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/adminlte/dist';

    public $css = [
        'css/AdminLTE.css',
        'css/skins/_all-skins.min.css'
    ];

    public $js = [
        'js/app.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'colibri\base\assets\FontawesomeAsset',
    ];

}