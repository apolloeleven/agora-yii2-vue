<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserComment;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserCommentQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class UserCommentQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return UserComment[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return UserComment|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return UserCommentQuery
     */
    public function byId($id)
    {
        return $this->andWhere([UserComment::tableName() . '.id' => $id]);
    }

    /**
     * @param $parentId
     * @return UserCommentQuery
     */
    public function byParentId($parentId)
    {
        return $this->andWhere([UserComment::tableName() . '.parent_id' => $parentId]);
    }

    /**
     * @param $timelinePostId
     * @return UserCommentQuery
     */
    public function byTimelinePostId($timelinePostId)
    {
        return $this->andWhere([UserComment::tableName() . '.timeline_post_id' => $timelinePostId]);
    }
}