<?php

namespace app\models\query;

use app\models\User;
use app\models\UserProfile;
use yii\db\ActiveQuery;

/**
 * Class UserProfileQuery
 *
 * @package app\models\query
 */
class UserProfileQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return array|UserProfile[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return array|UserProfile|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Get active users for user profile
     *
     * @return UserProfileQuery
     */
    public function active()
    {
        return $this->innerJoin(User::tableName() . ' u', 'u.id = ' . UserProfile::tableName() . '.user_id')
            ->andWhere(['status' => User::STATUS_ACTIVE, 'deleted_at' => null]);
    }
}