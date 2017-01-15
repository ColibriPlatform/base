<?php

namespace colibri\base\controllers;

use Yii;
use colibri\base\models\InstallForm;
use colibri\base\events\InstallEvent;
use colibri\base\components\Migration;

class InstallerController extends \yii\web\Controller
{
    /**
     * @event Event an event raised right before application installation.
     */
    const EVENT_BEFORE_INSTALL = 'beforeInstall';
    
    /**
     * @event Event an event raised right after application installation.
     */
    const EVENT_AFTER_INSTALL = 'afterInstall';
    
    /**
     * @event Event an event raised right before application update.
     */
    const EVENT_BEFORE_UPDATE = 'beforeUpdate';
    
    /**
     * @event Event an event raised right after application update.
     */
    const EVENT_AFTER_UPDATE = 'afterUpdate';


    public function actionIndex($lang='')
    {
        $this->layout = 'minimal';

        $model = new InstallForm();

        if (!empty($lang)) {
            Yii::$app->language = $lang;
            $model->language = $lang;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $this->createEnvFile($model);
            $this->loadEnv();
            $installMessages = $this->processMigrations();
            $this->createAdminUser($model);

            $event = new InstallEvent();
            $event->model = $model->getAttributes();
            $this->trigger(self::EVENT_AFTER_INSTALL, $event);
            $installMessages .= "\n" . $event->message;
        
            return $this->render('resume', [
                'messages' => $installMessages
            ]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    protected function processMigrations()
    {
        $messages = '';
        $userMigration = new Migration(Yii::getAlias('@vendor/dektrium/yii2-user/migrations'));
        $userMigration->up();
        $messages .= implode("\n", $userMigration->messages);

        $rbacMigration = new Migration(Yii::getAlias('@yii/rbac/migrations'));
        $rbacMigration->up();
        $messages .= implode("\n", $rbacMigration->messages);

        return $messages;
    }

    /**
     * @param InstallForm $model
     */
    protected function createEnvFile(InstallForm $model)
    {
        $envFile = Yii::getAlias('@app/.env');

        if (!file_exists($envFile) ) {

            $dsn = '';
            $buffer = "\n";
            $cookieKey = $this->generateRandomString();

            $buffer .= "YII_DEBUG         = 0\n";
            $buffer .= "YII_ENV           = prod\n";
            $buffer .= "APP_TIMEZONE      = {$model->timeZone}\n";
            $buffer .= "APP_LANGUAGE      = {$model->language}\n";
            $buffer .= "ADMIN_EMAIL       = {$model->email}\n";

            switch ($model->dbType) {
                case 'mysql':
                    $dsn = "mysql:host={$model->dbHost};dbname={$model->dbName}";
                    break;
            }

            $buffer .= "DB_DSN            = {$dsn}\n";
            $buffer .= "DB_USER           = {$model->dbUsername}\n";
            $buffer .= "DB_PASSWORD       = {$model->dbPassword}\n";
            $buffer .= "DB_TABLE_PREFIX   = {$model->dbTablePrefix}\n";

            $buffer .= "REQUEST_COOKIE_VALIDATION_KEY  = {$cookieKey}\n";
            $buffer .= "APP_CONFIG_FILE   = config.php\n";

            file_put_contents($envFile, $buffer);
        }
    }

    protected function loadEnv()
    {
        require Yii::getAlias('@vendor/ilhooq/colibri-base/config/env.php');

        // Setup the db object
        $db = Yii::$app->getDb();
        $db->dsn = getenv('DB_DSN');
        $db->username = getenv('DB_USER');
        $db->password = getenv('DB_PASSWORD');
        $db->tablePrefix = getenv('DB_TABLE_PREFIX');

        Yii::$app->language = getenv('APP_LANGUAGE');
        Yii::$app->setTimeZone(getenv('APP_TIMEZONE'));
    }

    protected function generateRandomString()
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('The OpenSSL PHP extension is required.');
        }
        $length = 32;
        $bytes = openssl_random_pseudo_bytes($length);
        return strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
    }

    protected function createAdminUser(InstallForm $model)
    {
        $userModel = new \dektrium\user\models\User();
        $userModel->scenario = 'create';
        $userModel->email = $model->email;
        $userModel->username = $model->login;
        $userModel->password = $model->password;
        $userModel->create();
    }

}
