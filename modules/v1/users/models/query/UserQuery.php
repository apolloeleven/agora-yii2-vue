<?php

namespace app\modules\v1\users\models\query;


use app\modules\v1\users\models\User;

/**
 * Class UserQuery
 *
 * @package app\modules\v1\users\models\query
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
     * Return active users
     *
     * @return mixed
     */
    public function active()
    {
        return $this->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
    }

    /**
     * Return non active users
     *
     * @return mixed
     */
    public function notActive()
    {
        return $this->andWhere([User::tableName() . '.status' => User::STATUS_INACTIVE]);
    }

    /**
     * Find users by email
     *
     * @param $email
     * @return mixed
     */
    public function byEmail($email)
    {
        return $this->andWhere([User::tableName() . '.email' => $email]);
    }

    /**
     * Find user by id
     *
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        return $this->andWhere([User::tableName() . '.id' => $id]);
    }

    /**
     * Find user by email or username
     *
     * @param $username
     * @return UserQuery
     */
    public function byEmailOrUsername($username)
    {
        return $this->andWhere([
            'OR',
            [User::tableName() . '.username' => $username],
            [User::tableName() . '.email' => $username]
        ]);
    }
}
