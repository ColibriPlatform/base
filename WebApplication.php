<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base;

use yii\helpers\ArrayHelper;

/**
 * Colibri Web application class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
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
        if (!isset($config['timeZone']) && getenv('APP_TIMEZONE')) {
            $config['timeZone'] = getenv('APP_TIMEZONE');
        }

        if (!isset($config['language']) && getenv('APP_LANGUAGE')) {
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
     * {@inheritDoc}
     * @see \yii\web\Application::bootstrap()
     */
    protected function bootstrap()
    {
        parent::bootstrap();

        $this->viewPath = __DIR__ . '/views';

        if (!isset($this->i18n->translations['colibri'])) {
            $this->i18n->translations['colibri'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages'
            ];
        }

        // Look in @app/messages directory for all categories
        if (!isset($this->i18n->translations['*'])) {
            $this->i18n->translations['*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/messages'
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

    /**
     * {@inheritDoc}
     * @see \yii\web\Application::coreComponents()
     */
    public function coreComponents()
    {
        return ArrayHelper::merge(parent::coreComponents(), [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => getenv('DB_DSN'),
                'username' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => 'utf8',
                'tablePrefix' => getenv('DB_TABLE_PREFIX'),
                'enableSchemaCache' => YII_ENV_PROD ? true : false,
            ],
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
                'cookieValidationKey' => getenv('REQUEST_COOKIE_VALIDATION_KEY')?
                                         getenv('REQUEST_COOKIE_VALIDATION_KEY') : 'Xd3456dZRE'
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
            'settings' => [
                'class' => 'colibri\base\Settings'
            ],
        ]);
    }
}
