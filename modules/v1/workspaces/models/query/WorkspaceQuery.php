<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class WorkspaceQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class WorkspaceQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return Workspace[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return Workspace|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return WorkspaceQuery
     */
    public function byId($id)
    {
        return $this->andWhere([Workspace::tableName() . '.id' => $id]);
    }

    /**
     * @param $userId
     * @return WorkspaceQuery
     */
    public function byUserId($userId)
    {
        return $this->innerJoin(UserWorkspace::tableName() . ' uw', 'uw.workspace_id = ' . Workspace::tableName() . '.id')
            ->andWhere(['user_id' => $userId]);
    }
}