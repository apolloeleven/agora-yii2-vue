<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 20:05
 */

namespace app\modules\v1\users\resources;


use app\modules\v1\users\models\Invitation;
use app\modules\v1\users\models\User;
use app\modules\v1\users\models\UserDepartment;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class UserResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\users\resources
 */
class UserResource extends User
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_WORKSPACE_ADMIN = 'workspaceAdmin';

    public $userDepartmentsData, $userWorkspacesData, $inlineUpdate;

    public function fields()
    {
        return [
            'id',
            'username',
            'first_name',
            'last_name',
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
            'displayName' => function () {
                return $this->getDisplayName();
            },
            'image_url' => function () {
                return $this->getImageUrl();
            },
            'access_token',
            'email',
            'status' => function () {
                return $this->status === 1;
            },
        ];
    }

    public function extraFields()
    {
        return ['userDepartments', 'userWorkspaces'];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [[
            ['userDepartmentsData', 'userWorkspacesData', 'inlineUpdate'], 'safe']
        ]);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $parentSave = parent::save($runValidation, $attributeNames);
            if (!$parentSave) {
                $transaction->rollBack();
            }
            if (!$this->inlineUpdate) {
                $this->updateRoles($this->userWorkspacesData);
                $this->updateUserWorkspaces($this->userWorkspacesData);
                $this->updateUserDepartments($this->userDepartmentsData);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $parentSave = false;
        }
        return $parentSave;
    }

    /**
     * @return ActiveQuery
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartmentResource::class, ['user_id' => 'id']);
    }

    /**
     * @return string[][]
     */
    public static function getUserRoles()
    {
        return [
            [
                'value' => self::ROLE_USER,
                'text' => Yii::t('app', 'User')
            ],
            [
                'value' => self::ROLE_ADMIN,
                'text' => Yii::t('app', 'Admin')
            ],
            [
                'value' => self::ROLE_WORKSPACE_ADMIN,
                'text' => Yii::t('app', 'Workspace Admin')
            ]
        ];
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function updateRoles($data)
    {
        $auth = Yii::$app->authManager;
        $rolesToDelete = [];
        $rolesFromPost = [];
        $userRoles = array_keys(ArrayHelper::getColumn($auth->getRolesByUser($this->id), 'name'));

        // Convert roles from post to array of strings
        foreach ($data as $role) {
            $rolesFromPost[] = $role['role'];
        }

        // Check if some roles should be deleted
        foreach ($userRoles as $userRole) {
            if (!in_array($userRole, $rolesFromPost)) {
                $rolesToDelete[] = $userRole;
            }
        }

        // Delete roles
        foreach ($rolesToDelete as $roleToDelete) {
            $roleModel = $auth->getRole($roleToDelete);
            $auth->revoke($roleModel, $this->id);
        }

        // Create new role if such does not exist
        foreach ($rolesFromPost as $role) {
            if (!in_array($role, $userRoles)) {
                $roleModel = $auth->getRole($role);
                $auth->assign($roleModel, $this->id);
            }
        }
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    public function updateUserDepartments($data)
    {
        $userDepartmentIdsFromPost = ArrayHelper::getColumn($data, 'id');
        $idsToBeDeleted = [];

        foreach ($this->userDepartments as $userDepartment) {
            if (!in_array($userDepartment->id, $userDepartmentIdsFromPost)) {
                $idsToBeDeleted[] = $userDepartment->id;
            }
        }

        if ($idsToBeDeleted) {
            $count = UserDepartment::deleteAll(['id' => $idsToBeDeleted]);
            if ($count !== count($idsToBeDeleted)) {
                throw new ValidationException(Yii::t('app', 'Error while deleting user departments'));
            }
        }

        $idToUserDepartmentMap = ArrayHelper::index($this->userDepartments, 'id');

        foreach ($data as $userDepartmentData) {
            if (in_array($userDepartmentData['id'], $idsToBeDeleted)) {
                continue;
            }

            $userDepartment = new UserDepartment();
            $userDepartment->user_id = $this->id;
            if (isset($idToUserDepartmentMap[$userDepartmentData['id']])) {
                $userDepartment = $idToUserDepartmentMap[$userDepartmentData['id']];
            }

            if (!$userDepartment->load($userDepartmentData, '') || !$userDepartment->save()) {
                throw new ValidationException(Yii::t('app', 'Error while saving user department'));
            }
        }
    }

    /**
     * Update user workspace data
     *
     * @param $data
     * @throws ValidationException
     */
    public function updateUserWorkspaces($data)
    {
        $existedIds = ArrayHelper::getColumn($data, 'id');
        $deletedIds = [];

        foreach ($this->userWorkspaces as $userWorkspace) {
            if (!in_array($userWorkspace->id, $existedIds)) {
                $deletedIds[] = $userWorkspace->id;
            }
        }

        if ($deletedIds) {
            if (UserWorkspace::deleteAll(['id' => $deletedIds]) !== count($deletedIds)) {
                throw new ValidationException(Yii::t('app', 'Error while deleting user workspaces'));
            }
        }

        $indexById = ArrayHelper::index($this->userWorkspaces, 'id');

        foreach ($data as $userWorkspaceData) {
            if (in_array($userWorkspaceData['id'], $deletedIds)) {
                continue;
            }

            $userWorkspace = new UserWorkspace();
            $userWorkspace->user_id = $this->id;
            if (isset($indexById[$userWorkspaceData['id']])) {
                $userWorkspace = $indexById[$userWorkspaceData['id']];
            }

            if (!$userWorkspace->load($userWorkspaceData, '') || !$userWorkspace->save()) {
                throw new ValidationException(Yii::t('app', 'Error while saving user workspaces'));
            }
        }
    }

    /**
     * Before save change invitation status from register to complete
     *
     * @param $insert
     * @return bool|void
     * @throws ValidationException
     * @throws \Exception
     */
    public function beforeSave($insert)
    {
        $oldStatus = ArrayHelper::getValue($this->oldAttributes, 'status');

        if ($oldStatus == self::STATUS_INACTIVE && $this->status == self::STATUS_ACTIVE && $this->invitation && $this->invitation->status == Invitation::STATUS_REGISTERED) {
            $this->invitation->status = Invitation::STATUS_COMPLETED;
            if (!$this->invitation->save()) {
                throw new ValidationException(Yii::t('app', "Unable to update Invitation. Token: {$this->invitation->token}"));
            }
        }
        return parent::beforeSave($insert);
    }

    // TODO after workspace will be ready change delete action
    public function beforeDelete()
    {
        UserDepartment::deleteAll(['user_id' => $this->id]);

        return parent::beforeDelete();
    }
}