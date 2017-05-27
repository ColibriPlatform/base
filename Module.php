<?php

namespace colibri\base;
use Yii;


class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{

    public $controllerNamespace = 'colibri\\base\\controllers';

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param \yii\web\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if (!file_exists($app->basePath . '/.env')) {
            // Remove query part
            $url = preg_replace('/\?.*$/', '', $app->getRequest()->url);

            if ($url != $app->getUrlManager()->createUrl(['base/installer'])) {
                $app->getResponse()->redirect(['base/installer']);
            }
        }
    }

    public function init()
    {
        parent::init();
    }


}
