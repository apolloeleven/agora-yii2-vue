<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;

/**
 * Class WorkspaceController
 *
 */
class WorkspaceController extends ActiveController
{
    public $modelClass = WorkspaceResource::class;
}