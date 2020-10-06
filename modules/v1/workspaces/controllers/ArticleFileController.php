<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\ArticleFileResource;
use app\rest\ActiveController;

/**
 * Class ArticleFileController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class ArticleFileController extends ActiveController
{
    public $modelClass = ArticleFileResource::class;
}