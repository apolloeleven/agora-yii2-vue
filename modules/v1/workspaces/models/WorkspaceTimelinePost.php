<?php

namespace app\modules\v1\workspaces\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%workspace_timeline_posts}}".
 *
 * @property int $id
 * @property int|null $workspace_id
 * @property int|null $timeline_post_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property TimelinePost $timelinePost
 * @property User $updatedBy
 * @property Workspace $workspace
 */
class WorkspaceTimelinePost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workspace_timeline_posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workspace_id', 'timeline_post_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['timeline_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimelinePost::class, 'targetAttribute' => ['timeline_post_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['workspace_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workspace::class, 'targetAttribute' => ['workspace_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'workspace_id' => 'Workspace ID',
            'timeline_post_id' => 'Timeline Post ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[TimelinePost]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\workspaces\models\query\TimelinePostQuery
     */
    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePost::class, ['id' => 'timeline_post_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Workspace]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\workspaces\models\query\WorkspaceQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery(get_called_class());
    }
}
