<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\models\UserActivity;
use app\rest\ActiveController;

class UserActivityController extends ActiveController
{
    public $modelClass = UserActivity::class;

}