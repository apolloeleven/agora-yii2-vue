<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\TimelinePostQuery;
use app\modules\v1\workspaces\models\query\WorkspaceQuery;
use app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class WorkspaceTimelinePost extends ActiveRecord
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
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ]);
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
            'id' => Yii::t('app', 'ID'),
            'workspace_id' => Yii::t('app', 'Workspace ID'),
            'timeline_post_id' => Yii::t('app', 'Timeline Post ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[TimelinePost]].
     *
     * @return ActiveQuery|TimelinePostQuery
     */
    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePost::class, ['id' => 'timeline_post_id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Workspace]].
     *
     * @return ActiveQuery|WorkspaceQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id']);
    }

    /**
     * {@inheritdoc}
     * @return WorkspaceTimelinePostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkspaceTimelinePostQuery(get_called_class());
    }
}
