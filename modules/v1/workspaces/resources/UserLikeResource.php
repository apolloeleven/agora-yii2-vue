<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserLike;
use yii\db\ActiveQuery;

/**
 * Class UserLikeResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class UserLikeResource extends UserLike
{
    public function fields()
    {
        return array_merge(Parent::fields(), [
            'createdBy'
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }
}