<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\models;

use Yii;
use yii\base\Model;

/**
 * InstallForm is the model behind the install form.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class InstallForm extends Model
{

    public $timeZone;

    public $language;

    public $dbType;

    public $dbName;

    public $dbUsername;

    public $dbPassword;

    public $dbHost;

    public $dbTablePrefix;

    public $email;

    public $login;

    public $password;

    public $globalError;


    /**
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules()
    {
        return [
            [['timeZone','dbType', 'language', 'dbName', 'email', 'login', 'password'], 'required'],
            ['dbType', 'in', 'range' => ['mysql','pgsql', 'sqlite']],
            [['dbTablePrefix'], 'safe'],
            [
                // These fields are required only if database is not Sqlite
                ['dbUsername', 'dbPassword', 'dbHost'],
                'required',
                'when' => function ($model) {
                    return $model->dbType != 'sqlite';
                },
                'whenClient' => "function(attribute, value) {return $('#installform-dbtype').val() != 'sqlite';}"
            ]
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels()
    {
        return [
            'timeZone'       => Yii::t('colibri', 'Time zone'),
            'language'       => Yii::t('colibri', 'Language'),
            'dbType'         => Yii::t('colibri', 'Db type'),
            'dbName'         => Yii::t('colibri', 'Db name'),
            'dbUsername'     => Yii::t('colibri', 'Db username'),
            'dbPassword'     => Yii::t('colibri', 'Db password'),
            'dbHost'         => Yii::t('colibri', 'Db host'),
            'dbTablePrefix'  => Yii::t('colibri', 'Table prefix'),
            'email'          => Yii::t('colibri', 'Email'),
            'login'          => Yii::t('colibri', 'Login'),
            'password'       => Yii::t('colibri', 'Password'),
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Model::attributeHints()
     */
    public function attributeHints()
    {
        return [
            'dbHost' => Yii::t('colibri', 'dbHost hint description'),
            'dbTablePrefix' => Yii::t('colibri', 'dbTablePrefix hint description'),
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Model::beforeValidate()
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        $dsn = '';
        switch ($this->dbType) {
            case 'mysql':
                $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName}";
                break;
        }

        // Test the Db
        $db = Yii::$app->getDb();
        $db->dsn = $dsn;
        $db->username = $this->dbUsername;
        $db->password = $this->dbPassword;
        $db->tablePrefix = $this->dbTablePrefix;
        try {
            $db->open();
        } catch (\Exception $e) {
            $this->globalError = $e->getMessage();
            return false;
        }
        return true;
    }
}
