<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery;
use app\modules\v1\workspaces\models\TimelinePost;
use app\rest\ValidationException;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class TimelinePostResource extends TimelinePost
{
    public $workspaceId;

    public function fields()
    {
        return array_merge(parent::fields(), [
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
        ]);
    }

    public function attributes()
    {
        return ArrayHelper::merge(array_keys(parent::attributeLabels()), ['workspaceId']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [[['workspaceId'], 'integer']]);
    }

    public function extraFields()
    {
        return ['workspace', 'workspaceTimelinePosts', 'workspaceId'];
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
     * @return WorkspaceTimelinePostQuery|ActiveQuery
     */
    public function getWorkspaceTimelinePosts()
    {
        return $this->hasMany(WorkspaceTimelinePostResource::class, ['timeline_post_id' => 'id']);
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws ErrorException
     * @throws Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $oldImage = $this->image_path;
        if ($this->image) {
            $this->image_path = "/storage/timeline/" . Yii::$app->security->generateRandomString(25) . '/' . $this->image->name;
        }
        $parentSave = parent::save($runValidation, $attributeNames);
        if (!$parentSave) {
            return $parentSave;
        }
        if ($this->image) {
            // Delete old image if it exists
            if ($oldImage) {
                $oldPath = Yii::getAlias("@webroot" . $oldImage);
                if (file_exists($oldPath)) {
                    FileHelper::removeDirectory(dirname($oldPath));
                }
            }

            $path = Yii::getAlias("@webroot") . $this->image_path;
            if (!is_dir(dirname($path))) {
                FileHelper::createDirectory(dirname($path));
            }
            $this->image->saveAs($path);
        }
        return $parentSave;
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
            $workspaceTimelinePosts->workspace_id = $this->workspaceId;
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
}