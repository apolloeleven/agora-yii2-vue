<?php

namespace app\modules\v1\setup\models\query;

use app\modules\v1\setup\models\Department;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\setup\models\Department]].
 *
 * @see \app\modules\v1\setup\models\Department
 */
class DepartmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\Department[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\Department|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return DepartmentQuery
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function byCountryId($id)
    {
        return $this->andWhere([Department::tableName() . '.country_id' => $id]);
    }
}
