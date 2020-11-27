<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\PollQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%polls}}".
 *
 * @property int $id
 * @property int $workspace_id
 * @property string|null $question
 * @property string|null $description
 * @property int|null $is_multiple
 * @property int|null $is_for_timeline
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property PollAnswer[] $pollAnswers
 * @property UserPoll[] $userPolls
 * @property User $createdBy
 * @property User $updatedBy
 * @property Workspace $workspace
 */
class Poll extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%polls}}';
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
            [['description'], 'string'],
            [['workspace_id'], 'required'],
            [['workspace_id', 'is_multiple', 'is_for_timeline', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['question'], 'string', 'max' => 1024],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'question' => Yii::t('app', 'Question'),
            'description' => Yii::t('app', 'Description'),
            'is_multiple' => Yii::t('app', 'Is Multiple'),
            'is_for_timeline' => Yii::t('app', 'Is For Timeline'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return PollQuery|ActiveQuery
     */
    public static function find()
    {
        return new PollQuery(get_called_class());
    }

    /**
     * Gets query for [[PollAnswers]].
     *
     * @return ActiveQuery
     */
    public function getPollAnswers()
    {
        return $this->hasMany(PollAnswer::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[PollAnswers]].
     *
     * @return ActiveQuery
     */
    public function getUserPolls()
    {
        return $this->hasMany(UserPoll::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Workspace]].
     *
     * @return ActiveQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['id' => 'workspace_id']);
    }
}
