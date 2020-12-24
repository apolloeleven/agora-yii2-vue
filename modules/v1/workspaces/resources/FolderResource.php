<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\setup\resources\UserResource;
use app\modules\v1\workspaces\models\Folder;
use yii\db\ActiveQuery;

/**
 * Class FolderResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class FolderResource extends Folder
{
    const VIDEO = 'video';

    public function fields()
    {
        return [
            'id',
            'parent_id',
            'workspace_id',
            'timeline_post_id',
            'name',
            'label',
            'depth',
            'size',
            'is_file',
            'is_timeline_folder',
            'url' => function () {
                return $this->getFileUrl();
            },
            'mime' => function () {
                return $this->mime;
            },
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
        ];
    }

    /**
     * @return array|string[]
     */
    public function extraFields(): array
    {
        return [
            'children',
            'workspace',
            'createdBy',
            'updatedBy',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUpdatedBy(): UserQuery
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }

}