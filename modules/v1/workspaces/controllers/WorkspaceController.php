<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\setup\resources\UserResource;
use app\modules\v1\workspaces\resources\UserWorkspaceResource;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

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
     * Invite users into workspace
     *
     * @return array|mixed
     * @throws Exception
     */
    public function actionInviteUsers()
    {
        $request = Yii::$app->request;
        $userIds = $request->post('selectedUsers');
        $workspaceId = $request->post('workspace_id');
        $role = $request->post('role');

        if (!$userIds) {
            return $this->validationError(Yii::t('app', 'Please select users'));
        }

        if (!UserResource::isValidRole($role)) {
            return $this->validationError(Yii::t('app', 'Invalid role'));
        }

        // Get all users which already exist this workspace
        $userWorkspaceIds = UserWorkspaceResource::find()
            ->select(UserWorkspaceResource::tableName() . '.user_id')
            ->byWorkspaceId($workspaceId)
            ->byUserIds($userIds)
            ->column();

        $userData = [];

        foreach ($userIds as $userId) {
            // Check if user is already member of workspace
            if (!in_array($userId, $userWorkspaceIds)) {
                $userData [] = [
                    'user_id' => $userId,
                    'workspace_id' => $workspaceId,
                    'role' => $role,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'created_by' => Yii::$app->user->id,
                    'updated_by' => Yii::$app->user->id,
                ];
            }
        }
        if (!$userData) {
            return $this->response(null, 200);
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        $createdData = Yii::$app->db->createCommand()
            ->batchInsert
            (
                UserWorkspaceResource::tableName(),
                ['user_id', 'workspace_id', 'role', 'created_at', 'updated_at', 'created_by', 'updated_by'],
                $userData
            )
            ->execute();

        if ($createdData !== count($userData)) {
            $dbTransaction->rollBack();
            return $this->validationError(Yii::t('app', 'Unable to invite user(s)'));
        }

        $dbTransaction->commit();
        return $this->response(null, 201);
    }
}