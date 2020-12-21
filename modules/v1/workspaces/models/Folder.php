<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\behaviors\ActivityBehavior;
use app\modules\v1\workspaces\models\query\FolderQuery;
use app\rest\ValidationException;
use creocoder\nestedsets\NestedSetsBehavior;
use WebPConvert\WebPConvert;
use Yii;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%folders}}".
 *
 * @property int           $id
 * @property int|null      $parent_id
 * @property int           $workspace_id
 * @property int           $timeline_post_id
 * @property int|null      $is_timeline_folder
 * @property int|null      $is_file
 * @property string|null   $name
 * @property string|null   $label
 * @property string|null   $data
 * @property string|null   $file_path
 * @property string|null   $mime
 * @property string|null   $content
 * @property int|null      $size
 * @property int|null      $lft
 * @property int|null      $rgt
 * @property int|null      $depth
 * @property int|null      $tree
 * @property int|null      $created_at
 * @property int|null      $updated_at
 * @property int|null      $created_by
 * @property int|null      $updated_by
 *
 * @property User          $createdBy
 * @property Folder        $parent
 * @property Folder[]      $children
 * @property User          $updatedBy
 * @property UserComment[] $userComments
 * @property UserLike[]    $userLikes
 * @property TimelinePost  $timelinePost
 * @property Workspace     $workspace
 */
class Folder extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%folders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = TimestampBehavior::class;
        $behaviors[] = BlameableBehavior::class;
        $behaviors['NestedSetsModel'] = [
            'class' => NestedSetsBehavior::class,
            'treeAttribute' => 'tree',
        ];
        $behaviors['activity'] = [
            'class' => ActivityBehavior::class,
            'workspace_id' => function () {
                return $this->workspace_id;
            },
            'events' => ['create', 'update', 'delete'],
            'eventMap' => [
                'create' => function () {
                    if ($this->is_file) {
                        return 'upload';
                    }

                    return 'create';
                },
                'delete' => function () {
                    if ($this->is_file) {
                        return 'delete_file';
                    }

                    return 'delete';
                }
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
            [['parent_id', 'workspace_id', 'timeline_post_id', 'is_timeline_folder', 'is_file', 'size', 'lft', 'rgt', 'depth', 'tree', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['workspace_id'], 'required'],
            [['data', 'content'], 'string'],
            [['name', 'label', 'file_path'], 'string', 'max' => 1024],
            [['mime'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::class, 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('app', 'Parent ID'),
            'workspace_id' => Yii::t('app', 'Workspace ID'),
            'timeline_post_id' => Yii::t('app', 'Timeline Post ID'),
            'is_timeline_folder' => Yii::t('app', 'Is Timeline Folder'),
            'is_file' => Yii::t('app', 'Is File'),
            'name' => Yii::t('app', 'Name'),
            'label' => Yii::t('app', 'Label'),
            'data' => Yii::t('app', 'Data'),
            'file_path' => Yii::t('app', 'File Path'),
            'mime' => Yii::t('app', 'Mime'),
            'content' => Yii::t('app', 'Content'),
            'size' => Yii::t('app', 'Size'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'tree' => Yii::t('app', 'Tree'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return FolderQuery
     */
    public static function find()
    {
        return new FolderQuery(get_called_class());
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
        return $this->hasOne(Folder::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Folders]].
     *
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Folder::class, ['parent_id' => 'id']);
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
    public function getTimelinePost()
    {
        return $this->hasOne(TimelinePost::class, ['id' => 'timeline_post_id']);
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

    /**
     * Get full path
     *
     * @return bool|string
     */
    public function getFullPath()
    {
        return Yii::getAlias('@storage' . $this->file_path);
    }

    /**
     * Get file url
     *
     * @return bool|string
     */
    public function getFileUrl()
    {
        return $this->file_path ? Yii::getAlias('@storageUrl' . $this->file_path) : '';
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return strpos($this->mime, 'image/') === 0;
    }

    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @param              $workspaceId
     * @return bool
     * @throws Exception
     * @throws ValidationException
     */
    public function uploadFile(UploadedFile $file, $workspaceId)
    {
        $path = "/file-manager/$workspaceId/" . date('Ymd', time());
        $fullPath = Yii::getAlias('@storage' . $path);

        if (!file_exists($fullPath) && !FileHelper::createDirectory($fullPath)) {
            throw new ValidationException(Yii::t('app', 'Unable to upload file'));
        }

        $this->name = $file->name;
        $this->mime = $file->type;
        $this->size = $file->size;
        $this->file_path = $path . '/' . Yii::$app->security->generateRandomString() . '.' . $file->extension;

        if (!$file->saveAs(Yii::getAlias('@storage/' . $this->file_path))) {
            throw new ValidationException(Yii::t('app', 'Unable to save file'));
        }

        return true;
    }

    /**
     * Convert uploaded file, then resize and save
     *
     * @param $filePath
     * @param $fileName
     * @return bool
     * @throws \WebPConvert\Convert\Exceptions\ConversionFailedException
     */
    public function convertUploadedFile($filePath, $fileName): bool
    {
        $src = Yii::getAlias("@storage{$filePath}");
        $newFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.webp';
        $destination = Yii::getAlias("@storage{$newFilePath}");
        WebPConvert::convert($src, $destination);

        $this->name = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
        $this->mime = 'image/webp';
        $this->file_path = $newFilePath;
        $this->size = filesize($destination);

        return true;
    }
}
