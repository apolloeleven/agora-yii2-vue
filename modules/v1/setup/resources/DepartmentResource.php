<?php
/**
 * User: zura
 * Date: 18.09.20
 * Time: 14:58
 */

namespace app\modules\v1\setup\resources;


use app\modules\v1\setup\models\Department;

/**
 * Class DepartmentResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\setup\resources
 */
class DepartmentResource extends Department
{
    public function fields()
    {
        return [
            'id', 'name', 'created_at' => function () {
                return \Yii::$app->formatter->asDatetime($this->created_at);
            }
        ];
    }

    public function extraFields()
    {
        return ['country', 'createdBy', 'parent'];
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
}