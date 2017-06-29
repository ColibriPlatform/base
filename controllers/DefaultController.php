<?php
namespace colibri\base\controllers;


class DefaultController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
