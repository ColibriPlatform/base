<?php
namespace colibri\base\events;

use yii\base\Event;


class InstallEvent extends Event
{
    public $model = [];
    public $message;
}