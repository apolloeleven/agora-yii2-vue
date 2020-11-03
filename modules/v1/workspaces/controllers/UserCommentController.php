<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\UserCommentResource;
use app\rest\ActiveController;

/**
 * Class UserCommentController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class UserCommentController extends ActiveController
{
    public $modelClass = UserCommentResource::class;
}