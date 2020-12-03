<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\WorkspaceActivity;

class WorkspaceActivityResource extends WorkspaceActivity
{
    public function fields()
    {
        return array_merge(parent::fields(), ['createdBy']);
    }
}