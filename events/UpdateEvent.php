<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

namespace modules\migration\events;

use yii\base\Event;

/**
 * Update Event class.
 *
 * @author Sylvain PHILIP <contact@sphilip.com>
 */
class UpdateEvent extends Event
{
    public $message;
}
