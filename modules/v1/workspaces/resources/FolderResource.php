<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Folder;
use app\rest\ValidationException;
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
            'is_file',
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
        return ['children', 'workspace', 'createdBy', 'updatedBy'];
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
     * Check folder and delete if has no sub-folders
     *
     * @return bool|int
     * @throws ValidationException
     */
    public function delete()
    {
        if ($this->getChildren()->count()) {
            throw new ValidationException(Yii::t('app', 'You can\'t delete this folder because it has sub-articles'));
        }
        $this->deleteWithChildren();

        return true;
    }
}