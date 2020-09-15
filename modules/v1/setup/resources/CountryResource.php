<?php
/**
 * User: zura
 * Date: 14.09.20
 * Time: 13:07
 */

namespace app\modules\v1\setup\resources;


use app\modules\v1\setup\models\Country;

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
        return ['createdBy'];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }
}