<?php

namespace colibri\base\components;

use yii\helpers\ArrayHelper;

class WebApplication extends \yii\web\Application
{

    /**
     * @var string the default route of this application. Defaults to 'default'.
     */
    public $defaultRoute = 'default';

    public $controllerNamespace = 'colibri\\base\\controllers';

    /**
     * {@inheritDoc}
     * @see \yii\base\Application::preInit()
     */
    public function preInit(&$config)
    {
        if (!isset($config['timeZone']) && getenv('APP_TIMEZONE'))
        {
            $config['timeZone'] = getenv('APP_TIMEZONE');
        }

        if (!isset($config['language']) && getenv('APP_LANGUAGE'))
        {
            $config['language'] = getenv('APP_LANGUAGE');
        }

        if (!isset($config['modules']['rbac'])) {
            $config['modules']['rbac'] = [
                'class' => 'dektrium\rbac\Module',
            ];
        }

        if (!isset($config['modules']['user'])) {
            $config['modules']['user'] = [
                'class' => 'dektrium\user\Module',
                'cost' => 12,
                'enableRegistration' => false,
                'enableConfirmation' => false,
                'enableUnconfirmedLogin' => false,
                'adminPermission' => 'admin',
                'mailer' => [
                    'sender'  => getenv('ADMIN_EMAIL'),
                ],
            ];
        }
        $config['bootstrap'][] = 'log';

        parent::preInit($config);
    }

    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {
        parent::bootstrap();

        $this->viewPath = dirname(__DIR__) . '/views';

        if (!isset($this->i18n->translations['colibri'])) {
            $this->i18n->translations['colibri'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => dirname(__DIR__). '/messages'
            ];
        }

        if (!file_exists($this->basePath . '/.env')) {

            // Remove query part
            $url = preg_replace('/\?.*$/', '', $this->getRequest()->url);

            if ($url != '/install') {
                $this->getResponse()->redirect(['/install']);
            }
        }
    }

    public function coreComponents()
    {
        return ArrayHelper::merge(parent::coreComponents(), [
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
            ],
            'assetManager' => [
                'linkAssets' => YII_ENV_DEV ? true : false,
            ],
            'request' => [
                //  This is required by cookie validation
                'cookieValidationKey' => getenv('REQUEST_COOKIE_VALIDATION_KEY')? getenv('REQUEST_COOKIE_VALIDATION_KEY') : 'Xd3456dZRE'
            ],
            'user' => [
                'identityClass' => 'dektrium\user\models\User',
                'enableAutoLogin' => true,
                'authTimeout' => 7200,
                'loginUrl' => ['user/security/login'],
                'returnUrl' => ['/'],
            ],
            'errorHandler' => [
                'errorAction' => 'default/error',
            ],
            'log' => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets' => [
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
            'view' => [
                'theme' => [
                    'basePath' => '@app/views',
                    'baseUrl' => '@web',
                    'pathMap' => [
                        '@colibri/base/views' => '@app/views',
                    ],
                ],
            ]
        ]);
    }
}