<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserPollAnswer;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserPollAnswerQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class UserPollAnswerQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return UserPollAnswer[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return UserPollAnswer|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return UserPollAnswerQuery
     */
    public function byId($id)
    {
        return $this->andWhere([UserPollAnswer::tableName() . '.id' => $id]);
    }
}