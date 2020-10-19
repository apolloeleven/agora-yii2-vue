<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery;
use app\modules\v1\workspaces\models\TimelinePost;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class TimelinePostResource extends TimelinePost
{
    public $workspace_id;

    public function fields()
    {
        return [
            'id',
            'action',
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

    public function attributes()
    {
        return ArrayHelper::merge(array_keys(parent::attributeLabels()), ['workspace_id']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [[['workspace_id'], 'integer']]);
    }

    public function extraFields()
    {
        return [
            'workspaceTimelinePosts',
            'createdBy',
            'article',
            'articleFiles',
            'timelineComments',
            'userLikes',
            'myLikes',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['id' => 'timeline_post_id'])
            ->via('workspaceTimelinePosts');
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
     * @return WorkspaceTimelinePostQuery|ActiveQuery
     */
    public function getWorkspaceTimelinePosts()
    {
        return $this->hasMany(WorkspaceTimelinePostResource::class, ['timeline_post_id' => 'id']);
    }

    /**
     * After save Timeline Post create new Workspace Timeline Post
     *
     * @param $insert
     * @param $changedAttributes
     * @throws ValidationException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $workspaceTimelinePosts = new WorkspaceTimelinePostResource();
            $workspaceTimelinePosts->workspace_id = $this->workspace_id;
            $workspaceTimelinePosts->timeline_post_id = $this->id;
            if (!$workspaceTimelinePosts->save()) {
                throw new ValidationException(\Yii::t('app', 'Unable to create Workspace Timeline Post'));
            }
        }
    }

    /**
     * Delete Workspace Timeline Posts before Timeline Post is deleted
     */
    public function beforeDelete()
    {
        WorkspaceTimelinePostResource::deleteAll(['timeline_post_id' => $this->id]);
        return parent::beforeDelete();
    }

    public function load($data, $formName = null)
    {
        $this->file = UploadedFile::getInstanceByName('file');

        return parent::load($data, $formName);
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->file) {
            return parent::save($runValidation, $attributeNames);
        }
        $dirPath = '/timelinePosts/' . $this->workspace_id;
        $this->file_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '.' . $this->file->extension;

        $parentSave = parent::save($runValidation, $attributeNames);
        if (!$parentSave) return $parentSave;

        $fullPath = Yii::getAlias('@storage' . $this->file_path);
        if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
        $this->file->saveAs($fullPath);

        return $parentSave;
    }

    /**
     * @return UserQuery|ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
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
}