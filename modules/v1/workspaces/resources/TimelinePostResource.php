<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\TimelinePost;
use app\rest\ValidationException;
use yii\helpers\ArrayHelper;

class TimelinePostResource extends TimelinePost
{
    public $workspaceId;

    public function fields()
    {
        return parent::fields();
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

    public function getWorkspace()
    {
        return $this->hasOne(WorkspaceResource::class, ['id' => 'timeline_post_id'])
            ->via('workspaceTimelinePosts');
    }

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
            $workspaceTimelinePosts->workspace_id = $this->workspaceId;
            $workspaceTimelinePosts->timeline_post_id = $this->id;
            if (!$workspaceTimelinePosts->save()) {
                throw new ValidationException(\Yii::t('app', 'Unable to create Workspace Timeline Post'));
            }
        }
    }

    /**
     * Delete Workspace Timeline Posts after Timeline Post is deleted
     */
    public function afterDelete()
    {
        parent::afterDelete();
        WorkspaceTimelinePostResource::deleteAll([
            'timeline_post_id' => $this->id
        ]);
    }
}