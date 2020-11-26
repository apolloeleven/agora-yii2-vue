<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\Poll;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class PollQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class PollQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return Poll[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return Poll|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return PollQuery
     */
    public function byId($id)
    {
        return $this->andWhere([Poll::tableName() . '.id' => $id]);
    }

    /**
     * @param $id
     * @return PollQuery
     */
    public function byWorkspaceId($id)
    {
        return $this->andWhere([Poll::tableName() . '.workspace_id' => $id]);
    }
}