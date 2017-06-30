<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\controllers;

/**
 * Default controller class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class DefaultController extends \yii\web\Controller
{

    /**
     * {@inheritDoc}
     * @see \yii\base\Controller::actions()
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    /**
     * Display the home page
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
