<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Folder;
use app\modules\v1\workspaces\models\TimelinePost;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

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
            'file_url' => function () {
                return $this->getFileUrl();
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
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['workspace_id' => 'id']);
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
     * Get timeline post file path
     *
     * @return bool|string
     */
    public function getFileUrl()
    {
        $folder = FolderResource::findOne(['timeline_post_id' => $this->id]);
        return $folder ? Yii::getAlias('@storageUrl' . $folder->file_path) : '';
    }

    public function load($data, $formName = null)
    {
        $this->file = UploadedFile::getInstanceByName('file');
        return parent::load($data, $formName);
    }

    /**
     * Save timeline file into default folder after upload
     *
     * @param $insert
     * @param $changedAttributes
     * @throws ValidationException
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert && $this->file) {
            $folder = new Folder();
            $folder->workspace_id = $this->workspace_id;
            $folder->timeline_post_id = $this->id;
            $folder->is_file = 1;

            $parentFolder = Folder::find()->byWorkspaceId($folder->workspace_id)->isTimelineFolder()->one();

            if (!$parentFolder) {
                throw new ValidationException(Yii::t('app', 'Unable to find parent folder'));
            }
            $folder->parent_id = $parentFolder->id;

            if (!$folder->uploadFile($this->file)) {
                throw new ValidationException(Yii::t('app', 'Unable to upload attachment'));
            }

            if (!$folder->appendTo($parentFolder)) {
                throw new ValidationException(Yii::t('app', 'Unable to upload file'));
            }
        }
    }
}