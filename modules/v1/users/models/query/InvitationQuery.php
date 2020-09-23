<?php

namespace app\modules\v1\users\models\query;

use app\modules\v1\users\models\Invitation;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\users\models\Invitation]].
 *
 * @see \app\modules\v1\users\models\Invitation
 */
class InvitationQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Invitation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Invitation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Return not used invitation link
     *
     * @return mixed
     */
    public function notUsed()
    {
        return $this->andWhere([Invitation::tableName() . '.use_date' => null]);
    }

    /**
     * Find invitations by token
     *
     * @param $token
     * @return mixed
     */
    public function byToken($token)
    {
        return $this->andWhere([Invitation::tableName() . '.token' => $token]);
    }

    /**
     * Find users by email
     *
     * @param $email
     * @return mixed
     */
    public function byEmail($email)
    {
        return $this->andWhere([Invitation::tableName() . '.email' => $email]);
    }
}
