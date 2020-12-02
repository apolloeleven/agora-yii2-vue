<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\behaviors\ActivityBehavior;
use app\modules\v1\workspaces\models\query\ArticleQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%articles}}".
 *
 * @property int           $id
 * @property int           $workspace_id
 * @property string|null   $title
 * @property string|null   $body
 * @property int|null      $created_at
 * @property int|null      $updated_at
 * @property int|null      $created_by
 * @property int|null      $updated_by
 *
 * @property User          $createdBy
 * @property UserComment[] $userComments
 * @property UserLike[]    $userLikes
 * @property UserLike[]    $myLikes
 * @property User          $updatedBy
 * @property Workspace     $workspace
 */
class Article extends ActiveRecord
{
    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%articles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = TimestampBehavior::class;
        $behaviors[] = BlameableBehavior::class;
        $behaviors['activity'] = [
            'class' => ActivityBehavior::class,
            'workspace_id' => function () {
                return $this->workspace_id;
            },
            'data' => function () {
                return $this;
            },
            'events' => ['create', 'update'],
            'template' => [
                'update' => 'Edited {model} {title}',
            ]
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workspace_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['workspace_id'], 'required'],
            [['body'], 'string'],
            [['title',], 'string', 'max' => 1024],
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
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return ArticleQuery
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    /**
     * Gets query for [[UserComments]].
     *
     * @return ActiveQuery
     */
    public function getUserComments()
    {
        return $this->hasMany(UserComment::class, ['article_id' => 'id']);
    }

    /**
     * Gets query for [[UserLikes]].
     *
     * @return ActiveQuery
     */
    public function getUserLikes()
    {
        return $this->hasMany(UserLike::class, ['article_id' => 'id']);
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
