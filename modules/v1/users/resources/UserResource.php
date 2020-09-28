<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 20:05
 */

namespace app\modules\v1\users\resources;


use app\models\User;
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
    public function fields()
    {
        return [
            'id',
            'username',
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
            'status',
        ];
    }

    public function extraFields()
    {
        return ['userProfile', 'userDepartments'];
    }

    /**
     * @return ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfileResource::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartmentResource::class, ['user_id' => 'id']);
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
    public function updateUserDepartments($data, $dbTransaction)
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
                $dbTransaction->rollBack();
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
                $dbTransaction->rollBack();
                throw new ValidationException(\Yii::t('app', 'Error while saving user department'));
            }
        }
    }
}