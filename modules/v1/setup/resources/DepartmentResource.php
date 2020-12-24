<?php
/**
 * User: zura
 * Date: 18.09.20
 * Time: 14:58
 */

namespace app\modules\v1\setup\resources;


use app\modules\v1\setup\models\Department;
use app\modules\v1\setup\resources\UserDepartmentResource;
use app\modules\v1\setup\resources\UserResource;
use app\rest\ValidationException;

/**
 * Class DepartmentResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\setup\resources
 */
class DepartmentResource extends Department
{
    public $children = [];

    public function fields()
    {
        return [
            'id', 'name', 'country_id', 'parent_id', 'children', 'created_at' => function () {
                return \Yii::$app->formatter->asDatetime($this->created_at);
            }
        ];
    }

    public function extraFields()
    {
        return ['country', 'createdBy', 'parent', 'userDepartments'];
    }

    /**
     * @return \app\modules\v1\setup\models\query\CountryQuery|\yii\db\ActiveQuery
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function getCountry()
    {
        return $this->hasOne(CountryResource::class, ['id' => 'country_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    public function beforeDelete()
    {
        if ($this->getDepartments()->count() > 0) {
            throw new ValidationException(\Yii::t('app', "You can't delete the department because it has child departments"));
        }
        return parent::beforeDelete();
    }

    public function getDataForDropdown() {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'country' => $this->country->name
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartmentResource::class, ['department_id' => 'id']);
    }
}
