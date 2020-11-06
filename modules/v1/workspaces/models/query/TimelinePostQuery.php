<?php

namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\TimelinePost;
use app\modules\v1\workspaces\models\WorkspaceTimelinePost;
use yii\db\ActiveQuery;


/**
 * Class TimelinePostQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class TimelinePostQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return TimelinePost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TimelinePost|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byWorkspaceId($workspaceId)
    {
        return $this
            ->innerJoin(WorkspaceTimelinePost::tableName() . ' wt', 'wt.timeline_post_id=' . TimelinePost::tableName() . '.id')
            ->andWhere(['wt.workspace_id' => $workspaceId]);
    }
}
