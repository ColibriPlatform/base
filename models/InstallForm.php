<?php
namespace colibri\models;

use Yii;
use yii\base\Model;


/**
 * InstallForm is the model behind the install form.
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

    public $email;

    public $login;

    public $password;



    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['timeZone','dbType', 'language', 'dbName', 'email', 'login', 'password'], 'required'],
            ['dbType', 'in', 'range' => ['mysql','pgsql', 'sqlite']],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'timeZone'   => Yii::t('colibri', 'Time zone'),
            'language'   => Yii::t('colibri', 'Language'),
            'dbType'     => Yii::t('colibri', 'Db type'),
            'dbName'     => Yii::t('colibri', 'Db name'),
            'dbUsername' => Yii::t('colibri', 'Db username'),
            'dbPassword' => Yii::t('colibri', 'Db password'),
            'dbHost'     => Yii::t('colibri', 'Db host'),
            'email'      => Yii::t('colibri', 'Email'),
            'login'      => Yii::t('colibri', 'Login'),
            'password'   => Yii::t('colibri', 'Password'),
        ];
    }
}
