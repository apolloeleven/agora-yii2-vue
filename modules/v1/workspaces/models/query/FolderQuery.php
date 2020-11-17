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

    /**
     * @return FolderQuery
     */
    public function byName($name)
    {
        return $this->andWhere([Folder::tableName() . '.name' => $name]);
    }

    /**
     * @return FolderQuery
     */
    public function isFile()
    {
        return $this->andWhere([Folder::tableName() . '.is_file' => 1]);
    }

    /**
     * @return FolderQuery
     */
    public function isTimelineFolder()
    {
        return $this->andWhere([Folder::tableName() . '.is_timeline_folder' => 1]);
    }

    /**
     * Get timeline posts which has file
     *
     * @return FolderQuery
     */
    public function hasTimelineFile()
    {
        return $this->andWhere(['NOT', [Folder::tableName() . '.timeline_post_id' => null]]);
    }

    /**
     * Get without timeline posts which has file
     *
     * @return FolderQuery
     */
    public function hasNoTimelineFile()
    {
        return $this->andWhere([Folder::tableName() . '.timeline_post_id' => null]);
    }
}