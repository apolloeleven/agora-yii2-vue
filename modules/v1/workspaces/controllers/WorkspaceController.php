<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\users\resources\UserResource;
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

    /**
     * Get all users for workspace invitation
     *
     * @return ActiveDataProvider
     */
    public function actionGetAllUser()
    {
        $query = UserResource::find()->active();

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     *
     */
    public function actionInviteUsers()
    {
        $request = Yii::$app->request;
        $selectedUsers = $request->post('selectedUsers');
        $allUser = $request->post('allUser');

        return $request;
        //TODO create user workspaces
    }
}