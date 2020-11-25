<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\resources\UserWorkspaceResource;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;

/**
 * Class WorkspaceController
 *
 */
class WorkspaceController extends ActiveController
{
    public $modelClass = WorkspaceResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create', 'delete', 'update'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['createWorkspace']
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['updateWorkspace']
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['deleteWorkspace']
                ],

            ]
        ];

        return $behaviors;
    }
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
        $selectedUsers = $request->post('selectedUsers');
        $allUser = $request->post('allUser');
        $workspaceId = $request->post('workspace_id');

        $userIds = $selectedUsers ? $selectedUsers : $allUser;

        if (!$userIds) {
            return $this->validationError(Yii::t('app', 'Please select users'));
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
                    'role' => UserResource::ROLE_USER,
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

    /**
     * Get users by workspace
     *
     * @return array|mixed
     * @throws \Exception
     */
    public function actionGetUsers()
    {
        $workspaceId = Yii::$app->request->get('id');
        if (!$workspaceId) {
            return new ValidationException(Yii::t('app', 'Please, provide the workspace id'));
        }

        $users = [];
        $userWorkspaces = UserWorkspaceResource::find()
            ->byWorkspaceId($workspaceId)
            ->with(['user'])
            ->all();

        foreach ($userWorkspaces as $userWorkspace) {
            $id = $userWorkspace->user->id;

            if (!isset($users[$id])) {
                $users[$id] = $userWorkspace->user->toArray();
                $users[$id]['roles'] = [$userWorkspace->role];
            } else {
                $users[$id]['roles'][] = $userWorkspace->role;
            }
        }

        return $users;
    }
}