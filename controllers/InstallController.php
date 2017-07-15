<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\controllers;

use Yii;
use yii\rbac\Role;
use colibri\base\Migration;
use colibri\base\models\InstallForm;

/**
 * Install controller class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class InstallController extends \yii\web\Controller
{
    /**
     * Display the install form
     *
     * @param string $lang The lang to use
     *
     * @return string
     */
    public function actionIndex($lang = '')
    {
        $model = new InstallForm();

        if (!empty($lang)) {
            Yii::$app->language = $lang;
            $model->language = $lang;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $this->createEnvFile($model);

            Yii::$app->language = $model->language;
            Yii::$app->setTimeZone($model->timeZone);

            $installMessages = $this->processMigrations();
            $this->initRbac($model);

            $messages = $this->callModulesMethod('migrateUp');
            $installMessages .= implode('\n', $messages);

            $messages = $this->callModulesMethod('afterInstall');
            $installMessages .= implode('\n', $messages);

            return $this->render('resume', [
                'messages' => $installMessages
            ]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * Process required migrations
     *
     * @return string
     */
    protected function processMigrations()
    {
        $messages = '';

        $migrationsPaths = [
            '@vendor/dektrium/yii2-user/migrations',
            '@yii/rbac/migrations',
            '@pheme/settings/migrations',
        ];

        foreach ($migrationsPaths as $path)
        {
            $migration = new Migration(Yii::getAlias($path));
            $migration->up();
            $messages .= implode("\n", $migration->messages);
        }

        return $messages;
    }

    /**
     * Create the application env file
     *
     * @param InstallForm $model
     *
     * @return void
     */
    protected function createEnvFile(InstallForm $model)
    {
        $envFile = Yii::getAlias('@app/.env');

        if (!file_exists($envFile)) {

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

            file_put_contents($envFile, $buffer);
        }
    }

    /**
     * Generate a random string
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function generateRandomString()
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('The OpenSSL PHP extension is required.');
        }

        $length = 32;
        $bytes = openssl_random_pseudo_bytes($length);

        return strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
    }

    /**
     * Init Rbac
     *
     * @param InstallForm $model
     *
     * @return void
     */
    protected function initRbac(InstallForm $model)
    {
        $auth = Yii::$app->authManager;

        $rule = new \colibri\base\rbac\RegisteredRule();

        if (!$auth->getRule($rule->name)) {
            $auth->add($rule);
        }

        // Create the registered role
        if (!$auth->getRole('registered')) {

            $registered = new Role([
                'name' => 'registered',
                'ruleName' => $rule->name,
                'description' => Yii::t('colibri', 'Default role for registered users')
            ]);
            $auth->add($registered);
        }

        // Create the application admin user
        $user = new \dektrium\user\models\User();

        $user->scenario = 'create';
        $user->email = $model->email;
        $user->username = $model->login;
        $user->password = $model->password;
        $user->confirmed_at = time();
        $user->save();

        if (!$auth->getRole('admin')) {
            $admin = new Role(['name' => 'admin', 'description' => Yii::t('colibri', 'Administrators')]);
            $auth->add($admin);
            $auth->addChild($admin, $registered);
            $auth->assign($admin, $user->id);
        }
    }


    /**
     * Call a method if exists on every application modules
     *
     * @param string $methodName The method to call
     *
     * @return mixed[]
     */
    protected function callModulesMethod($methodName)
    {
        $modules = Yii::$app->getModules();
        $returns = [];

        foreach ($modules as $moduleName => $module)
        {
            if (is_array($module)) {
                $module = Yii::$app->getModule($moduleName);
            }

            if (method_exists($module, $methodName)) {
                $returns[] = $module->$methodName();
            }
        }

        return $returns;
    }
}
