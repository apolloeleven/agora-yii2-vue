<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserPoll;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserPollQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class UserPollQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return UserPoll[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return UserPoll|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return UserPollQuery
     */
    public function byId($id)
    {
        return $this->andWhere([UserPoll::tableName() . '.id' => $id]);
    }
}