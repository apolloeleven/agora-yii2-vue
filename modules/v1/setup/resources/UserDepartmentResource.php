<?php

namespace app\modules\v1\setup\resources;


use app\modules\v1\setup\models\UserDepartment;
use Yii;

class UserDepartmentResource extends UserDepartment
{
    public function fields()
    {
        return [
            'id',
            'user_id',
            'department_id',
            'position',
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },

        ];
    }

    public function extraFields()
    {
        return ['department', 'user'];
    }

    public function getDepartment()
    {
        return $this->hasOne(DepartmentResource::class, ['id' => 'department_id']);
    }

    /**
     * @return \app\modules\v1\setup\models\query\UserQuery|\yii\db\ActiveQuery
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getUser()
    {
        return $this->hasOne(UserResource::class, ['id' => 'user_id']);
    }
}