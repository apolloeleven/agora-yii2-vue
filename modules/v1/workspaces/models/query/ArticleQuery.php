<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\Article;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ArticleQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class ArticleQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return Article[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return Article|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return ArticleQuery
     */
    public function byId($id)
    {
        return $this->andWhere([Article::tableName() . '.id' => $id]);
    }

    /**
     * @param $workspaceId
     * @return ArticleQuery
     */
    public function byWorkspaceId($workspaceId)
    {
        return $this->andWhere([Article::tableName() . '.workspace_id' => $workspaceId]);
    }

    /**
     * @param $parentId
     * @return ArticleQuery
     */
    public function byParentId($parentId)
    {
        return $this->andWhere([Article::tableName() . '.parent_id' => $parentId]);
    }

    /**
     * @return ArticleQuery
     */
    public function roots()
    {
        return $this->andWhere([Article::tableName() . '.depth' => 0]);
    }
}