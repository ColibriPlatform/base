<?php

namespace colibri\base\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_admin
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'email'], 'required'],
            [['password'], 'required', 'on' => SELF::SCENARIO_CREATE],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            ['username', 'unique', 'message' => 'Ce nom d\'utilisteur existe déjà.'],
            ['email', 'unique', 'message' => 'Cet email est déjà pris.'],
            ['email', 'email'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_admin'], 'integer'],
            [['username', 'name', 'email'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6, 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Nom d\'utilisateur',
            'name' => 'Nom',
            'email' => 'Email',
            'password' => 'Mot de passe',
            'auth_key' => 'Auth Key',
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_admin' => 'Administrateur',
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['username', 'name', 'email', 'password', 'is_admin'],
            self::SCENARIO_UPDATE => ['username', 'name', 'email', 'password', 'is_admin'],
        ];
    }
    
    /* (non-PHPdoc)
     * @see \yii\base\Model::load($data, $formName)
     */
    public function load($data, $formName = null)
    {
        $oldPassword = $this->password;

        if (!parent::load($data, $formName)) {
            return false;
        }

        if (empty($this->password) && !empty($oldPassword)) {
            // Garde l'ancien mot de passe dans le cas d'une mise à jour ou le mot de passe n'est pas renseigné
            $this->password = $oldPassword;
        }

        return true;
    }

    /* (non-PHPdoc)
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->auth_key  = Yii::$app->getSecurity()->generateRandomString();
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }
        if (!empty($this->password)) {
            $this->password  = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        return true;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        //return $this->password === $password;
    }
}
