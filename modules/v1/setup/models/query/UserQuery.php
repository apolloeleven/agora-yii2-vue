<?php

namespace app\modules\v1\setup\models\query;


use app\modules\v1\setup\models\User;
use app\modules\v1\workspaces\models\UserWorkspace;

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
     * @param null $alias
     * @return \app\modules\v1\setup\models\query\UserQuery
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function active($alias = null)
    {
        $a = $alias ?: User::tableName();

        return $this->andWhere([$a . '.status' => User::STATUS_ACTIVE]);
    }

    /**
     * Return non active users
     *
     * @return self
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
     * @param $id
     * @return \app\modules\v1\setup\models\query\UserQuery
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
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

    /**
     * Get users by workspace id
     *
     * @param $workspaceId
     * @return UserQuery
     */
    public function byWorkspaceId($workspaceId)
    {
        return $this
            ->innerJoin(UserWorkspace::tableName() . ' uw', 'uw.user_id = ' . User::tableName() . '.id')
            ->andWhere(['uw.workspace_id' => $workspaceId]);
    }


    /**
     * Order users by name
     *
     * @return UserQuery
     */
    public function orderByName()
    {
        return $this->orderBy([
            User::tableName() . '.first_name' => SORT_ASC,
            User::tableName() . '.last_name' => SORT_ASC
        ]);
    }
}
