<?php
/**
 * Created By Nika Gelashvili
 * Date: 02.10.20
 * Time: 14:24
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\WorkspaceTimelinePost;

class WorkspaceTimelinePostResource extends WorkspaceTimelinePost
{
    public function fields()
    {
        return parent::fields();
    }

    public function extraFields()
    {
        return ['workspace', 'timelinePost'];
    }

    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['id' => 'workspace_id']);
    }

    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePostResource::class, ['id' => 'timeline_post_id']);
    }
}