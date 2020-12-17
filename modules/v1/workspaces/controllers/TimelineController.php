<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 12:37
 */

namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
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
        $lastPostId = (int)Yii::$app->request->get('last_post_id');
        $workspaceId = Yii::$app->request->get('workspace_id');

        $query = TimelinePostResource::find()
            ->alias('t')
            ->innerJoin(Workspace::tableName().' w', 'w.id = t.workspace_id')
            ->innerJoin(UserWorkspace::tableName(). ' uw', 'uw.workspace_id = w.id')
            ->andWhere(['uw.user_id' => Yii::$app->user->id])
            ->limit($limit);
        if ($lastPostId) {
            $query->andWhere(['<', 't.id', $lastPostId]);
        }

        if ($workspaceId) {
            $query = $query->byWorkspaceId(Yii::$app->request->get('workspace_id'));
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }
}