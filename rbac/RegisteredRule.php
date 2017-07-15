<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\rbac;

use Yii;
use yii\rbac\Rule;

/**
 * Registered rule class for RBAC.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class RegisteredRule extends Rule
{
    public $name = 'registered';

    /**
     * {@inheritDoc}
     * @see \yii\rbac\Rule::execute()
     */
    public function execute($user, $item, $params)
    {
        return !Yii::$app->getUser()->isGuest;
    }
}
