<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\PollResource;
use app\rest\ActiveController;

/**
 * Class PollController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class PollController extends ActiveController
{
    public $modelClass = PollResource::class;
}