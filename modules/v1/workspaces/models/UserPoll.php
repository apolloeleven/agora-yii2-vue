<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\UserPollQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_polls}}".
 *
 * @property int $id
 * @property int $poll_answer_id
 * @property int $user_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property PollAnswer $pollAnswer
 * @property User $updatedBy
 * @property User $user
 */
class UserPoll extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{$user_polls}}';
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
            [['poll_answer_id', 'user_id'], 'required'],
            [['poll_answer_id', 'user_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['poll_answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => PollAnswer::class, 'targetAttribute' => ['poll_answer_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'poll_answer_id' => Yii::t('app', 'Poll Answer ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return UserPollQuery|ActiveQuery
     */
    public static function find()
    {
        return new UserPollQuery(get_called_class());
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
     * Gets query for [[PollAnswer]].
     *
     * @return ActiveQuery
     */
    public function getPollAnswer()
    {
        return $this->hasOne(PollAnswer::class, ['id' => 'poll_answer_id']);
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
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
