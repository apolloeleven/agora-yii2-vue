<?php
/**
 * Created By Nika Gelashvili
 * Date: 24.09.20
 * Time: 16:28
 */

namespace app\modules\v1\users\resources;


use app\models\User;
use app\modules\v1\users\models\UserRole;
use Yii;

class UserRoleResource extends UserRole
{
    const ROLE_USER = 'User';
    const ROLE_ADMIN = 'Admin';
    const ROLE_WORKSPACE_ADMIN = 'Workspace Admin';

    public function fields()
    {
        return [
            'id',
            'role',
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
        ];
    }

    public function extraFields()
    {
        return [
            'user'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getUserRoles() {
        return [
            [
                'value' => 'user',
                'text' => self::ROLE_USER
            ],
            [
                'value' => 'admin',
                'text' => self::ROLE_ADMIN
            ],
            [
                'value' => 'workspaceAdmin',
                'text' => self::ROLE_WORKSPACE_ADMIN
            ]
        ];
    }
}