<?php

namespace app\modules\v1\workspaces\models;

use app\models\User;
use app\modules\v1\workspaces\models\query\ArticleFileQuery;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%article_files}}".
 *
 * @property int $id
 * @property int|null $article_id
 * @property string|null $name
 * @property string|null $path
 * @property string|null $mime
 * @property string|null $content
 * @property int|null $size
 * @property int|null $to_process
 * @property int|null $processing
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property Article $article
 * @property User $createdBy
 * @property User $updatedBy
 */
class ArticleFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_files}}';
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
            [['article_id', 'size', 'to_process', 'processing', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['name', 'mime'], 'string', 'max' => 255],
            [['path'], 'string', 'max' => 512],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
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
            'article_id' => Yii::t('app', 'Article ID'),
            'name' => Yii::t('app', 'Name'),
            'path' => Yii::t('app', 'Path'),
            'mime' => Yii::t('app', 'Mime'),
            'content' => Yii::t('app', 'Content'),
            'size' => Yii::t('app', 'Size'),
            'to_process' => Yii::t('app', 'To Process'),
            'processing' => Yii::t('app', 'Processing'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return ArticleFileQuery
     */
    public static function find()
    {
        return new ArticleFileQuery(get_called_class());
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
     * Gets query for [[UpdatedBy]].
     *
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @return bool
     * @throws Exception
     */
    public function uploadFile(UploadedFile $file)
    {
        $path = '/article-attachments/' . $this->article_id;
        $fullPath = Yii::getAlias('@storage' . $path);

        $this->name = $file->name;
        $this->path = $path;
        $this->mime = $file->type;
        $this->size = $file->size;

        if (!file_exists($fullPath)) if (!FileHelper::createDirectory($fullPath)) {
            throw new ValidationException(Yii::t('app', "Directory '$fullPath' was NOT created"));
        }
        $this->path = $path . '/' . Yii::$app->security->generateRandomString() . '.' . $file->extension;

        if (!$file->saveAs(Yii::getAlias('@storage/' . $this->path))) {
            throw new ValidationException(Yii::t('app', "File was NOT uploaded at '$this->path'"));
        }
        return true;
    }
}
