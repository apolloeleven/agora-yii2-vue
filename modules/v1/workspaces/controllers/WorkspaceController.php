<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\resources\UserWorkspaceResource;
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
     *
     */
    public function actionInviteUsers()
    {
        $request = Yii::$app->request;
        $selectedUsers = $request->post('selectedUsers');
        $allUser = $request->post('allUser');
        $workspaceId = $request->post('workspace_id');

        $userIds = $selectedUsers ? $selectedUsers : $allUser;

        if (!$userIds) {
            return $this->validationError(Yii::t('app', 'Please select users'));
        }

        foreach ($userIds as $userId) {
            $userWorkspace = UserWorkspaceResource::find()->byWorkspaceId($workspaceId)->byUserId($userId)->one();
            if (!$userWorkspace) {
                $model = new UserWorkspaceResource();

                $model->user_id = $userId;
                $model->workspace_id = $workspaceId;
                $model->role = UserResource::ROLE_USER;

                if ((!$model->load($request->post(), '')) || !$model->save()) {
                    return $this->validationError($model->getFirstErrors());
                }
            }
        }
        return $this->response(null, 201);
    }
}