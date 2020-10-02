<?php

namespace app\modules\v1\workspaces\models;

use app\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
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
class TimelinePost extends \yii\db\ActiveRecord
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'image_path' => 'Image Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'image' => 'Image'
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[WorkspaceTimelinePosts]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\workspaces\models\query\WorkspaceTimelinePostQuery
     */
    public function getWorkspaceTimelinePosts()
    {
        return $this->hasMany(WorkspaceTimelinePost::class, ['timeline_post_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\query\TimelinePostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\v1\workspaces\models\query\TimelinePostQuery(get_called_class());
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $oldImage = $this->image_path;
        if ($this->image) {
            $this->image_path = "/storage/timeline/" . Yii::$app->security->generateRandomString(25) . '/' . $this->image->name;
        }
        $parentSave = parent::save($runValidation, $attributeNames);
        if (!$parentSave) {
            return $parentSave;
        }
        if ($this->image) {
            // Delete old image if it exists
            if ($oldImage) {
                $oldPath = Yii::getAlias("@webroot" . $oldImage);
                if (file_exists($oldPath)) {
                    FileHelper::removeDirectory(dirname($oldPath));
                }
            }

            $path = Yii::getAlias("@webroot") . $this->image_path;
            if (!is_dir(dirname($path))) {
                FileHelper::createDirectory(dirname($path));
            }
            $this->image->saveAs($path);
        }
        return $parentSave;
    }
}
