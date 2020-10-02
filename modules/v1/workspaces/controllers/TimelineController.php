<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 12:37
 */

namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\models\search\TimelinePostSearch;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\resources\TimelinePostResource;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;

class TimelineController extends ActiveController
{
    public $modelClass = TimelinePostResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'delete'],
                    //TODO roles
                ]
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = new TimelinePostSearch();
        return $query->search(\Yii::$app->request->get());
    }

    public function actionGetDropdownData()
    {
        $workspace = WorkspaceResource::find()
            ->innerJoinWith(['userWorkspaces'])
            ->andWhere([UserWorkspace::tableName() . '.user_id' => \Yii::$app->user->id]);

    }
}