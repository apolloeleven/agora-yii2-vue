<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\setup\models\query\UserQuery;
use app\modules\v1\setup\models\User;
use app\modules\v1\workspaces\behaviors\ActivityBehavior;
use app\modules\v1\workspaces\workspaceBehaviours\UrlAnchorBehaviour;
use app\modules\v1\workspaces\models\query\TimelinePostQuery;
use app\modules\v1\workspaces\models\query\WorkspaceQuery;
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
 * @property int $workspace_id
 * @property int $article_id
 * @property string|null $attachment_ids
 * @property string|null $action
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Article $article
 * @property UserLike[] $myLikes
 * @property UserComment[] $userComments
 * @property UserLike[] $userLikes
 * @property User $createdBy
 * @property User $updatedBy
 * @property Workspace[] $workspace
 * @property Folder[] $folders
 */
class TimelinePost extends ActiveRecord
{
    const ACTION_SHARE_ARTICLE = "SHARE_ARTICLE";

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
            [
                'class' => UrlAnchorBehaviour::class,
                'attributes' => ['description']
            ],
            [
                'class' => ActivityBehavior::class,
                'workspace_id' => function () {
                    return $this->workspace_id;
                },
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workspace_id'], 'required'],
            [['description', 'attachment_ids'], 'string'],
            [['article_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'workspace_id'], 'integer'],
            [['action'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['workspace_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workspace::class, 'targetAttribute' => ['workspace_id' => 'id']],
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
            'workspace_id' => Yii::t('app', 'Workspace ID'),
            'article_id' => Yii::t('app', 'Article ID'),
            'attachment_ids' => Yii::t('app', 'Attachment IDs'),
            'action' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Description'),
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
     * @return ActiveQuery|WorkspaceQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::class, ['workspace_id' => 'id']);
    }

    /**
     * @return ActiveQuery|\app\modules\v1\workspaces\models\query\FolderQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::class, ['timeline_post_id' => 'id']);
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
