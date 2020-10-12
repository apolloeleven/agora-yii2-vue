<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\TimelinePostQuery;
use app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%timeline_posts}}".
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $image_path
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property WorkspaceTimelinePost[] $workspaceTimelinePosts
 */
class TimelinePost extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%timeline_posts}}';
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
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['image_path'], 'string', 'max' => 1024],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, svg, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Description'),
            'image_path' => Yii::t('app', 'Image Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'image' => Yii::t('app', 'Image'),
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
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[WorkspaceTimelinePosts]].
     *
     * @return ActiveQuery|WorkspaceTimelinePostQuery
     */
    public function getWorkspaceTimelinePosts()
    {
        return $this->hasMany(WorkspaceTimelinePost::class, ['timeline_post_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TimelinePostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TimelinePostQuery(get_called_class());
    }
}
