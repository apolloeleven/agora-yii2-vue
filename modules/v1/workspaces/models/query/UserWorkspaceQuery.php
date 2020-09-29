<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserWorkspace;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserWorkspaceQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class UserWorkspaceQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return UserWorkspace[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return UserWorkspace|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Get records by user id
     *
     * @param $userId
     * @return UserWorkspaceQuery
     */
    public function byUserId($userId)
    {
        return $this->andWhere([UserWorkspace::tableName() . '.user_id' => $userId]);
    }

    /**
     * Get records by workspace id
     *
     * @param $workspaceId
     * @return UserWorkspaceQuery
     */
    public function byWorkspaceId($workspaceId)
    {
        return $this->andWhere([UserWorkspace::tableName() . '.workspace_id' => $workspaceId]);
    }
}