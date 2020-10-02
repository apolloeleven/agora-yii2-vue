<?php
/**
 * Created By Nika Gelashvili
 * Date: 23.09.20
 * Time: 18:04
 */

namespace app\modules\v1\users\resources;


use app\modules\v1\setup\resources\DepartmentResource;
use app\modules\v1\users\models\UserDepartment;
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
        return ['department'];
    }

    public function getDepartment()
    {
        return $this->hasOne(DepartmentResource::class, ['id' => 'department_id']);
    }
}