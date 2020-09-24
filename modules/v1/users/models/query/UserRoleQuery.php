<?php

namespace app\modules\v1\users\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\users\models\UserRole]].
 *
 * @see \app\modules\v1\users\models\UserRole
 */
class UserRoleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\users\models\UserRole[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\users\models\UserRole|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
