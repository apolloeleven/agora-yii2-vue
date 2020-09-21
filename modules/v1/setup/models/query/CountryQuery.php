<?php

namespace app\modules\v1\setup\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\setup\models\Country]].
 *
 * @see \app\modules\v1\setup\models\Country
 */
class CountryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\Country[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\Country|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
