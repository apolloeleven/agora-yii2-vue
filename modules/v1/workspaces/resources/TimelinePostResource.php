<?php
/**
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\helpers\ModelHelper;
use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\TimelinePost;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * Class TimelinePostResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\workspaces\resources
 *
 * @property \app\modules\v1\workspaces\resources\FolderResource[] $folders
 */
class TimelinePostResource extends TimelinePost
{
    const IS_FILE = 1;

    /**
     * @return array
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function fields()
    {
        return [
            'id',
            'action',
            'workspace_id',
            'description',
            'files' => function () {
                return $this->getFiles();
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
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function extraFields()
    {
        return [
            'createdBy',
            'updatedBy',
            'article',
            'timelineComments',
            'userLikes',
            'myLikes',
            'workspace',
            'folders'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['id' => 'workspace_id']);
    }

    /**
     * Gets query for [[TimelineComments]].
     *
     * @return ActiveQuery
     */
    public function getTimelineComments()
    {
        return $this->hasMany(UserCommentResource::class, ['timeline_post_id' => 'id'])->orderBy('created_at DESC');
    }

    /**
     * Gets query for [[UserLikes]].
     *
     * @return ActiveQuery
     */
    public function getUserLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['timeline_post_id' => 'id']);
    }

    /**
     * @return UserQuery|ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * @return UserQuery|ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(ArticleResource::class, ['id' => 'article_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMyLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['timeline_post_id' => 'id'])
            ->andWhere(['created_by' => Yii::$app->user->id]);
    }

    /**
     * @return ActiveQuery|\app\modules\v1\workspaces\models\query\FolderQuery
     */
    public function getFolders()
    {
        return $this->hasMany(FolderResource::class, ['timeline_post_id' => 'id']);
    }

    /**
     * Get timeline post file path
     *
     * @return array
     */
    public function getFiles(): array
    {
        $folders = $this->getFolders()->all();
        $urls = [];
        foreach ($folders as $folder) {
            $url = [
                'original' => [
                    'key' => 'original',
                    'url' => $folder->getFileUrl(),
                    'mime' => $folder->mime,
                    'name' => $folder->name,
                    'size' => $folder->size
                ]
            ];
            if ($folder->isImage()) {
                $files = Json::decode($folder->data);
                if ($files) {
                    foreach ($files as $key => $file) {
                        $url[$key] = [
                            'key' => $key,
                            'url' => Yii::getAlias('@storageUrl') . $file['path'],
                            'mime' => $file['mime'],
                            'name' => $file['name'],
                            'size' => $file['size']
                        ];
                    }
                }
            }
            $urls[] = $url;
        }

        return $urls;
    }

    public function load($data, $formName = null)
    {
        $this->file = UploadedFile::getInstanceByName('file');

        return parent::load($data, $formName);
    }

    /**
     * Save timeline file into default folder after upload
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     * @throws \app\rest\ValidationException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function save($runValidation = true, $attributeNames = null): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        $insert = $this->isNewRecord;
        $check = parent::save($runValidation, $attributeNames);
        if (!$check) {
            return false;
        }
        if ($insert && $this->file) {
            $parentFolder = FolderResource::find()->byWorkspaceId($this->workspace_id)->isTimelineFolder()->one();

            if (!$parentFolder) {
                throw new ValidationException(Yii::t('app', 'Unable to find parent original'));
            }


            $original = new FolderResource();
            $original->workspace_id = $this->workspace_id;
            $original->timeline_post_id = $this->id;
            $original->is_file = 1;
            $original->parent_id = $parentFolder->id;

            if (!$original->uploadFile($this->file, $original->workspace_id)) {
                throw new ValidationException(Yii::t('app', 'Unable to upload attachment'));
            }

            if (!$original->appendTo($parentFolder)) {
                throw new ValidationException(Yii::t('app', 'Unable to upload file'));
            }
            $transaction->commit();

            Yii::$app->async->execute("async/process-image", $original->id);
            return true;
        }
        $transaction->commit();
        return true;
    }

    /**
     * Before delete timeline post delete file from storage
     *
     * @return bool
     * @throws ValidationException
     */
    public function beforeDelete()
    {
        $folders = FolderResource::find()->byTimelineId($this->id)->all();

        if (!$folders) {
            return parent::beforeDelete();
        }

        foreach ($folders as $folder) {
            if (file_exists(Yii::getAlias("@storage/" . $folder->file_path)) && !ModelHelper::deleteFile($folder->file_path)) {
                throw new ValidationException(Yii::t('app', 'Unable to delete timeline file'));
            }
        }

        FolderResource::deleteAll(['timeline_post_id' => $this->id]);

        return parent::beforeDelete();
    }
}