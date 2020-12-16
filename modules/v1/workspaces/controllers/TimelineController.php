<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 12:37
 */

namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\TimelinePostResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class TimelineController extends ActiveController
{
    public $modelClass = TimelinePostResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'create', 'delete', 'update'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'delete', 'update'],
                    //TODO roles
                ]
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $limit  = Yii::$app->request->get('limit') ?? 20;
        $last_post_id = Yii::$app->request->get('last_post_id');

        $condition = ['<', 'id', $last_post_id];
        if (!$last_post_id) $condition = ['>', 'id', 0];

        $query = TimelinePostResource::find()
            ->where($condition)
            ->byWorkspaceId(Yii::$app->request->get('workspace_id'))
            ->limit($limit);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }
}