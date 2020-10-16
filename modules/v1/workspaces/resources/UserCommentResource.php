<?php


namespace app\modules\v1\workspaces\resources;

use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserComment;
use yii\db\ActiveQuery;

/**
 * Class UserCommentResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class UserCommentResource extends UserComment
{
    public function fields()
    {
        return [
            'id',
            'comment',
            'article_id',
            'parent_id',
            'timeline_post_id',
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            }
        ];
    }

    public function extraFields()
    {
        return ['createdBy', 'updatedBy', 'childrenComments', 'parent'];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[ChildrenComments]].
     *
     * @return ActiveQuery
     */
    public function getChildrenComments()
    {
        return $this->hasMany(UserCommentResource::class, ['parent_id' => 'id'])->orderBy('created_at DESC');
    }
}