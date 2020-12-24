<?php


namespace app\modules\v1\workspaces\resources;

use app\modules\v1\users\resources\UserProfileResource;
use app\modules\v1\workspaces\models\UserWorkspace;

/**
 * Class UserWorkspaceResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class UserWorkspaceResource extends UserWorkspace
{
    public function fields()
    {
        return [
            'id',
            'workspace_id',
            'user_id',
            'role'
        ];
    }

    public function extraFields()
    {
        return ['workspace'];
    }

    public function getUser()
    {
        return $this->hasOne(UserProfileResource::class, ['id' => 'user_id']);
    }
}