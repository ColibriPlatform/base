<?php

namespace colibri\base\controllers;

use Yii;
use colibri\base\models\InstallForm;

class InstallerController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'minimal';
        $model = new InstallForm();

        $preferredLanguage = Yii::$app->request->getPreferredLanguage();
        Yii::$app->language = $preferredLanguage;


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // $this->createLocalConfig($model);
            // $migrationMessages = $this->processMigrations();
            // $this->createAdmin($model);

            // $event = new InstallEvent();
            // $event->model = $model->getAttributes();
            // $this->trigger(self::EVENT_AFTER_INSTALL, $event);
            // $migrationMessages .= "\n" . $event->message;
        
            return $this->render('resumeinstall', [
                // 'messages' => $migrationMessages
            ]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

}
