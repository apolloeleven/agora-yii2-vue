<?php

namespace app\models\query;

use app\models\Invitation;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Invitation]].
 *
 * @see \app\models\Invitation
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
        return $this->andWhere(['use_date' => null]);
    }

    /**
     * Find invitations by token
     *
     * @param $token
     * @return mixed
     */
    public function byToken($token)
    {
        return $this->andWhere(['token' => $token]);
    }
}
