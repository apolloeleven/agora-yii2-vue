<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\PollAnswer;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class PollAnswerQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class PollAnswerQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return PollAnswer[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return PollAnswer|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return PollAnswerQuery
     */
    public function byId($id)
    {
        return $this->andWhere([PollAnswer::tableName() . '.id' => $id]);
    }

    /**
     * @param $id
     * @return PollAnswerQuery
     */
    public function byPollId($id)
    {
        return $this->andWhere([PollAnswer::tableName() . '.poll_id' => $id]);
    }
}