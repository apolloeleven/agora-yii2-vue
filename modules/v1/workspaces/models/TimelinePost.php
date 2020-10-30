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
use yii\helpers\Json;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%timeline_posts}}".
 *
 * @property int $id
 * @property int $article_id
 * @property string|null $attachment_ids
 * @property string|null $action
 * @property string|null $description
 * @property string|null $file_path
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Article $article
 * @property ArticleFile[] $articleFiles
 * @property User $createdBy
 * @property User $updatedBy
 * @property WorkspaceTimelinePost[] $workspaceTimelinePosts
 */
class TimelinePost extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file = null;

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
            [['description', 'attachment_ids'], 'string'],
            [['article_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['file_path'], 'string', 'max' => 1024],
            [['action'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, svg, gif, jpg, avi, flv, wmv, mov, mp4, ogg']
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
            'attachment_ids' => Yii::t('app', 'Attachment IDs'),
            'action' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Description'),
            'file_path' => Yii::t('app', 'File Path'),
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
     * @return ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id']);
    }

    /**
     * Gets query for [[ArticleFiles]].
     *
     * @return ActiveQuery
     */
    public function getArticleFiles()
    {
        return $this->hasMany(ArticleFile::class, ['article_id' => 'article_id']);
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

    /**
     * Get timeline post file path
     *
     * @return bool|string
     */
    public function getFileUrl()
    {
        return $this->file_path ? Yii::getAlias('@storageUrl' . $this->file_path) : '';
    }

    /**
     * Before check validate encode shared attachments id
     *
     * @return bool
     */
    public function beforeValidate()
    {
        $this->attachment_ids = Json::encode($this->attachment_ids);

        return parent::beforeValidate();
    }
}
