<?php

namespace app\modules\v1\users\models\query;


use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\UserWorkspace;

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
     * Return active users
     *
     * @return mixed
     */
    public function active()
    {
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
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

    /**
     * Find user by id
     *
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
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
