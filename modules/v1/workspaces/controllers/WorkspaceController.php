<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class WorkspaceController
 *
 */
class WorkspaceController extends ActiveController
{
    public $modelClass = WorkspaceResource::class;

    /**
     * Get workspaces by users
     *
     * @return ActiveDataProvider
     */
    public function actionGetWorkspaces()
    {
        $query = WorkspaceResource::find()->byUserId(Yii::$app->user->id);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}