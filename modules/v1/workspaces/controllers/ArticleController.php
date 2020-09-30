<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;

/**
 * Class ArticleController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class ArticleController extends ActiveController
{
    public $modelClass = ArticleResource::class;
}