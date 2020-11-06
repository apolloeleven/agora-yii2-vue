<?php

namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\WorkspaceTimelinePost;
use yii\db\ActiveQuery;

/**
 * Class WorkspaceTimelinePostQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class WorkspaceTimelinePostQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     *
     * @return WorkspaceTimelinePost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     *
     * @return WorkspaceTimelinePost|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return WorkspaceTimelinePostQuery
     */
    public function byWorkspaceId($id)
    {
        return $this->andWhere([WorkspaceTimelinePost::tableName() . '.workspace_id' => $id]);
    }

    /**
     * @param $id
     * @return WorkspaceTimelinePostQuery
     */
    public function byTimelinePostId($id)
    {
        return $this->andWhere([WorkspaceTimelinePost::tableName() . '.timeline_post_id' => $id]);
    }
}
