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

    public $roles, $userDepartmentsData;

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
            'roles' => function () {
                return $this->getRoles();
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
        return ['userDepartments'];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [[['roles', 'userDepartmentsData'], 'safe']]);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $parentSave = parent::save($runValidation, $attributeNames);
            if (!$parentSave) {
                $transaction->rollBack();
            }
            if ($this->roles) {
                $this->updateRoles($this->roles);
            }
            if ($this->userDepartmentsData) {
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
            $rolesFromPost[] = $role['name'];
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
     * @param $dbTransaction
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
                throw new ValidationException(\Yii::t('app', 'Error while deleting user departments'));
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
                throw new ValidationException(\Yii::t('app', 'Error while saving user department'));
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

        if ($oldStatus == self::STATUS_INACTIVE && $this->status == self::STATUS_ACTIVE && $this->invitation) {
            if ($this->invitation->status == Invitation::STATUS_REGISTERED) {
                $this->invitation->status = Invitation::STATUS_COMPLETED;
                if (!$this->invitation->save()) {
                    throw new ValidationException(Yii::t('app', "Unable to update Invitation. Token: {$this->invitation->token}"));
                }
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