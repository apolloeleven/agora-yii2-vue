<?php

namespace app\models\query;

use app\models\User;

/**
 * This is the ActiveQuery class for [[\app\models\User]].
 *
 * @see \app\models\User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Get user by id
     *
     * @param $id
     * @return UserQuery
     */
    public function byId($id)
    {
        return $this->andWhere([User::tableName() . '.id' => $id]);
    }

    /**
     * Return active users
     *
     * @return mixed
     */
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * Get not deleted users
     *
     * @return UserQuery
     */
    public function notDeleted()
    {
        return $this->andWhere([User::tableName() . '.deleted_at' => null]);
    }

    /**
     * Find users by email
     *
     * @param $email
     * @return mixed
     */
    public function byEmail($email)
    {
        return $this->andWhere(['email' => $email]);
    }
}
