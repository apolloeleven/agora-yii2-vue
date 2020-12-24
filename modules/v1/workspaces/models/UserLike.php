<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\setup\models\User;
use app\modules\v1\workspaces\behaviors\ActivityBehavior;
use app\modules\v1\workspaces\models\query\UserLikeQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_likes}}".
 *
 * @property int $id
 * @property int|null $article_id
 * @property int|null $timeline_post_id
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property Article $article
 * @property User $createdBy
 * @property TimelinePost $timelinePost
 */
class UserLike extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_likes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => TimestampBehavior::class,
            'updatedAtAttribute' => false,
        ];
        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'updatedByAttribute' => false,
        ];
        $behaviors['activity'] = [
            'class' => ActivityBehavior::class,
            'workspace_id' => function () {
                return $this->timelinePost->workspace_id;
            },
            'tableName' => TimelinePost::tableName(),
            'data' => function () {
                return $this->timelinePost;
            },
            'eventMap' => [
                'create' => 'like',
                'delete' => 'unlike',
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
            [['article_id', 'timeline_post_id', 'created_at', 'created_by'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['timeline_post_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimelinePost::class, 'targetAttribute' => ['timeline_post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_id' => Yii::t('app', 'Article ID'),
            'timeline_post_id' => Yii::t('app', 'Timeline Post ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return UserLikeQuery|ActiveQuery
     */
    public static function find()
    {
        return new UserLikeQuery(get_called_class());
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
     * Gets query for [[TimelinePost]].
     *
     * @return ActiveQuery
     */
    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePost::class, ['id' => 'timeline_post_id']);
    }
}
