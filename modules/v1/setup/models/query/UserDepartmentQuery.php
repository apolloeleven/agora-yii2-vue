<?php

namespace app\modules\v1\setup\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\setup\models\UserDepartment]].
 *
 * @see \app\modules\v1\setup\models\UserDepartment
 */
class UserDepartmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\UserDepartment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\UserDepartment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Find departments by user_id
     *
     * @param $user_id
     * @return UserDepartmentQuery
     */
    public function byUserId($user_id) {
        return $this->andWhere(['user_id' => $user_id]);
    }
}
