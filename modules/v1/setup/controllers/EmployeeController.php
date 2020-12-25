<?php
/**
 * Created By Nika Gelashvili
 * Date: 23.09.20
 * Time: 17:25
 */

namespace app\modules\v1\setup\controllers;


use app\modules\v1\setup\resources\UserDepartmentResource;
use app\modules\v1\setup\resources\UserResource;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\resources\UserWorkspaceResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class EmployeeController extends ActiveController
{
    public $modelClass = UserResource::class;

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

        return $actions;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['get-dropdown'] = ['GET', 'HEAD', 'OPTIONS'];
        $verbs['active-users'] = ['GET', 'HEAD', 'OPTIONS'];
        $verbs['change-role'] = ['POST', 'PUT', 'OPTIONS'];

        return $verbs;
    }

    public function actionGetDropdown()
    {
        $data = [
            'userRoles' => [],
            'userPositions' => [],
        ];

        $data['userRoles'] = UserResource::getUserRoles();

        $positionOptions = UserDepartmentResource::find()
            ->select(['position'])
            ->distinct()
            ->asArray()
            ->all();

        $data['userPositions'] = ArrayHelper::getColumn($positionOptions, 'position');

        return $data;
    }

    /**
     * Get all users for workspace invitation
     *
     * @return ActiveDataProvider
     */
    public function actionActiveUsers()
    {
        $query = UserResource::find()->active();

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     * Get users by workspace
     *
     * @param $workspaceId
     * @return array|mixed
     */
    public function actionByWorkspace($workspaceId)
    {
        if (!$workspaceId) {
            return new ValidationException(Yii::t('app', 'Please, provide the workspace id'));
        }

        $users = [];
        /** @var \app\modules\v1\setup\resources\UserResource[] $dbUsers */
        $dbUsers = UserResource::find()
            ->alias('u')
            ->innerJoin(UserWorkspaceResource::tableName(). ' uw', 'uw.user_id = u.id')
            ->andWhere(['workspace_id' => $workspaceId])
            ->with(['userWorkspace' => function($query) use($workspaceId) {
                /** @var \app\modules\v1\workspaces\models\query\UserWorkspaceQuery $query */
                $query->andWhere(['workspace_id' => $workspaceId]);
            }])
            ->all();

        foreach ($dbUsers as $dbUser) {
            $user = $dbUser->toArray();
            $user['role'] = $dbUser->userWorkspace->role;
            $users[] = $user;
        }

        return $users;
    }

    public function actionChangeRole()
    {
        $role = \Yii::$app->request->post('role');
        $userId = \Yii::$app->request->post('userId');
        $workspaceId = \Yii::$app->request->post('workspaceId');

        /** @var UserResource $user */
        $user = UserResource::find()->active()->byId($userId)->one();
        if (!$user) {
            Yii::error("User does not exist with ID=$userId", self::class);
            throw new InvalidArgumentException();
        }
        $userWorkspace = $user->getUserWorkspace()->byWorkspaceId($workspaceId)->one();
        if (!$userWorkspace) {
            Yii::error("User is not added in workspace ID=$workspaceId", self::class);
            throw new InvalidArgumentException();
        }

        $userWorkspace->role = $role;
        if ($userWorkspace->save()) {
            return $this->response(null);
        }
        return $this->validationError($userWorkspace->errors);

    }

    public function actionRemoveFromWorkspace()
    {
        $userId = \Yii::$app->request->post('userId');
        $workspaceId = \Yii::$app->request->post('workspaceId');

        /** @var UserResource $user */
        $user = UserResource::find()->active()->byId($userId)->one();
        if (!$user) {
            Yii::error("User does not exist with ID=$userId", self::class);
            throw new InvalidArgumentException();
        }
        $userWorkspace = $user->getUserWorkspace()->byWorkspaceId($workspaceId)->one();
        if (!$userWorkspace) {
            throw new InvalidArgumentException("User is not in workspace ID=$workspaceId");
        }

        if ($userWorkspace->delete()) {
            return $this->response(null);
        }

        return $this->validationError($userWorkspace->errors);

    }
}