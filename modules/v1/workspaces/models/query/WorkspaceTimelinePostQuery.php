<?php

namespace app\modules\v1\workspaces\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\workspaces\models\WorkspaceTimelinePost]].
 *
 * @see \app\modules\v1\workspaces\models\WorkspaceTimelinePost
 */
class WorkspaceTimelinePostQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\WorkspaceTimelinePost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\WorkspaceTimelinePost|array|null
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
        return $this->andWhere(['workspace_id' => $id]);
    }

    /**
     * @param $id
     * @return WorkspaceTimelinePostQuery
     */
    public function byTimelinePostId($id) {
        return $this->andWhere(['timeline_post_id' => $id]);
    }
}
