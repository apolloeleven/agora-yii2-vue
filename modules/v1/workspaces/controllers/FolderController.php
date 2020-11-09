<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\FolderResource;
use app\rest\ActiveController;

/**
 * Class FolderController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class FolderController extends ActiveController
{
    public $modelClass = FolderResource::class;
}