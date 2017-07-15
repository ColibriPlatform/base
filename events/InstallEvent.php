<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace colibri\base\events;

use yii\base\Event;

/**
 * Install Event class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class InstallEvent extends Event
{
    /**
     * @var array InstallForm model attributes
     */
    public $model = [];

    /**
     * @var string Event message to return
     */
    public $message = '';
}
