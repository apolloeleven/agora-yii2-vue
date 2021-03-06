<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\setup\models\User;
use app\modules\v1\workspaces\models\query\WorkspaceQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%workspaces}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $abbreviation
 * @property string|null $description
 * @property string|null $image_path
 * @property int $folder_in_folder
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Article[] $articles
 * @property User $createdBy
 * @property User $updatedBy
 * @property TimelinePost[] $timelinePosts
 * @property Folder[] $folders
 * @property Workspace[] $userWorkspaces
 */
class Workspace extends ActiveRecord
{
    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workspaces}}';
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
            [['name'], 'required'],
            [['description'], 'string'],
            [['folder_in_folder', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image_path'], 'string', 'max' => 1024],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, svg, gif, jpg']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'image_path' => Yii::t('app', 'Image Path'),
            'folder_in_folder' => Yii::t('app', 'Folder in Folder'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return WorkspaceQuery|ActiveQuery
     */
    public static function find()
    {
        return new WorkspaceQuery(get_called_class());
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
     * @return ActiveQuery
     */
    public function getUserWorkspaces()
    {
        return $this->hasMany(UserWorkspace::class, ['workspace_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['workspace_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::class, ['workspace_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTimelinePosts()
    {
        return $this->hasMany(TimelinePost::class, ['workspace_id' => 'id']);
    }
}
