<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserPoll;
use yii\db\ActiveQuery;

/**
 * Class UserPollResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class UserPollResource extends UserPoll
{
    public function extraFields()
    {
        return ['createdBy'];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }
}