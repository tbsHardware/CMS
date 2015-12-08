<?php

namespace app\components;

use yii\web\User;

class WebUser extends User
{
    public $logoutUrl;
    public $profileUrl;
}