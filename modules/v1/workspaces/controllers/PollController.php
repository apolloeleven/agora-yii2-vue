<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\PollResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class PollController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class PollController extends ActiveController
{
    public $modelClass = PollResource::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = PollResource::find()->byWorkspaceId(Yii::$app->request->get('workspace_id'));
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}