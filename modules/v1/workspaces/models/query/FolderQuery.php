<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\Folder;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class FolderQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class FolderQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::class
        ];
    }

    /**
     * @param null $db
     * @return Folder[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return Folder|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return FolderQuery
     */
    public function byId($id)
    {
        return $this->andWhere([Folder::tableName() . '.id' => $id]);
    }

    /**
     * @param $workspaceId
     * @return FolderQuery
     */
    public function byWorkspaceId($workspaceId)
    {
        return $this->andWhere([Folder::tableName() . '.workspace_id' => $workspaceId]);
    }

    /**
     * @param $parentId
     * @return FolderQuery
     */
    public function byParentId($parentId)
    {
        return $this->andWhere([Folder::tableName() . '.parent_id' => $parentId]);
    }

    /**
     * @return FolderQuery
     */
    public function roots()
    {
        return $this->andWhere([Folder::tableName() . '.depth' => 0]);
    }
}