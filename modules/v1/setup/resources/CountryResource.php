<?php
/**
 * User: zura
 * Date: 14.09.20
 * Time: 13:07
 */

namespace app\modules\v1\setup\resources;


use app\modules\v1\setup\models\Country;
use app\modules\v1\users\resources\UserResource;
use app\rest\ValidationException;

/**
 * Class CountryResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\setup\resources
 */
class CountryResource extends Country
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
        return ['createdBy', 'departments'];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    public function beforeDelete()
    {
        if ($this->getDepartments()->count() > 0) {
            throw new ValidationException(\Yii::t('app', "You can't delete the country because it has departments"));
        }
        return parent::beforeDelete();
    }

    /**
     * @return \app\modules\v1\setup\models\query\DepartmentQuery|\yii\db\ActiveQuery
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function getDepartments()
    {
        return $this->hasMany(DepartmentResource::class, ['country_id' => 'id']);
    }
}
