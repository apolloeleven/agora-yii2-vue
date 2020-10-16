<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\UserCommentQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_comments}}".
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $article_id
 * @property int|null $timeline_post_id
 * @property string|null $comment
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Article $article
 * @property User $createdBy
 * @property UserComment $parent
 * @property UserComment[] $userComments
 * @property TimelinePost $timelinePost
 * @property User $updatedBy
 */
class UserComment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_comments}}';
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
            [['parent_id', 'article_id', 'timeline_post_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['comment'], 'string'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserComment::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['timeline_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimelinePost::class, 'targetAttribute' => ['timeline_post_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'article_id' => Yii::t('app', 'Article ID'),
            'timeline_post_id' => Yii::t('app', 'Timeline Post ID'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return UserCommentQuery|ActiveQuery
     */
    public static function find()
    {
        return new UserCommentQuery(get_called_class());
    }

    /**
     * Gets query for [[Article]].
     *
     * @return ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
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
     * Gets query for [[Parent]].
     *
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(UserComment::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[UserComments]].
     *
     * @return ActiveQuery
     */
    public function getUserComments()
    {
        return $this->hasMany(UserComment::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[TimelinePost]].
     *
     * @return ActiveQuery
     */
    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePost::class, ['id' => 'timeline_post_id']);
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
}
