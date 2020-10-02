<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\TimelinePost;

class TimelinePostResource extends TimelinePost
{
    public function fields()
    {
        return parent::fields();
    }

    public function extraFields()
    {
        return ['workspace'];
    }

    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['id' => 'timeline_post_id'])
            ->via('workspaceTimelinePosts');
    }

    public function getWorkspaceTimelinePosts()
    {
        return $this->hasMany(WorkspaceTimelinePostResource::class, ['timeline_post_id' => 'id']);
    }
}