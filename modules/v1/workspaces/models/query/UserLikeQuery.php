<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\UserComment;
use app\modules\v1\workspaces\models\UserLike;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class UserLikeQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class UserLikeQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return UserLike[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return UserLike|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return UserLikeQuery
     */
    public function byId($id)
    {
        return $this->andWhere([UserLike::tableName() . '.id' => $id]);
    }

    /**
     * @param $articleId
     * @return UserLikeQuery
     */
    public function byArticleId($articleId)
    {
        return $this->andWhere([UserLike::tableName() . '.article_id' => $articleId]);
    }

    /**
     * @param $timelinePostId
     * @return UserLikeQuery
     */
    public function byTimelinePostId($timelinePostId)
    {
        return $this->andWhere([UserLike::tableName() . '.timeline_post_id' => $timelinePostId]);
    }
}