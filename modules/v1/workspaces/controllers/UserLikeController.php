<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\UserLikeResource;
use app\rest\ActiveController;

/**
 * Class UserLikeController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class UserLikeController extends ActiveController
{
    public $modelClass = UserLikeResource::class;
}