<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\components;

use yii\helpers\ArrayHelper;

/**
 * Colibri Console application class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class CliApplication extends \yii\console\Application
{
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
        ]);
    }
}
