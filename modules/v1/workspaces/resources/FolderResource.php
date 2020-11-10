<?php


namespace app\modules\v1\workspaces\resources;


use app\helpers\ModelHelper;
use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Folder;
use app\rest\ValidationException;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

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
        return ['children', 'workspace', 'createdBy', 'updatedBy', 'folderComments', 'userLikes', 'myLikes'];
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


    /**
     * Gets query for [[ArticleComments]].
     *
     * @return ActiveQuery
     */
    public function getFolderComments()
    {
        return $this->hasMany(UserCommentResource::class, ['folder_id' => 'id'])->orderBy('created_at DESC');
    }

    /**
     * Gets query for [[UserLikes]].
     *
     * @return ActiveQuery
     */
    public function getUserLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['folder_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMyLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['folder_id' => 'id'])
            ->andWhere(['created_by' => Yii::$app->user->id]);
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

    /**
     * Load for image upload
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this->image = UploadedFile::getInstanceByName('image');

        return parent::load($data, $formName);
    }

    /**
     * Upload image
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     * @throws ErrorException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->image) {
            return parent::save($runValidation, $attributeNames);
        }
        if (ModelHelper::isImage($this->image->extension)) {
            ModelHelper::deleteImage($this->file_path);

            $dirPath = '/folders';
            $this->mime = $this->image->type;
            $this->size = $this->image->size;
            $this->file_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

            $fullPath = Yii::getAlias('@storage' . $this->file_path);
            if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
            if (!$this->image->saveAs($fullPath, false)) {
                throw new ValidationException(Yii::t('app', 'File not uploaded'));
            }
        }

        return parent::save($runValidation, $attributeNames);
    }

}