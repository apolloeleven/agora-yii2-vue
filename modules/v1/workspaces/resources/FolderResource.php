<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Folder;
use Yii;
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
            'name',
            'body',
            'label',
            'depth',
            'size',
            'is_file',
            'is_timeline_folder',
            'file_path',
            'mime' => function () {
                $mime = explode('/', $this->mime)[0];
                return $mime == self::VIDEO ? $mime : $this->mime;
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
    public function extraFields()
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
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }
}