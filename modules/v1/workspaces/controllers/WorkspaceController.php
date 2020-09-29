<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class WorkspaceController
 *
 */
class WorkspaceController extends ActiveController
{
    public $modelClass = WorkspaceResource::class;

    public function actionGetUserWorkspaces()
    {
        $query = WorkspaceResource::find()
            ->innerJoinWith(['userWorkspaces'])
            ->andWhere([UserWorkspace::tableName() . '.user_id' => Yii::$app->user->id]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}