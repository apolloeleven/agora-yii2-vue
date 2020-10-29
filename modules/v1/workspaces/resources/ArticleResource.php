<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Article;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\StringHelper;

/**
 * Class ArticleResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class ArticleResource extends Article
{
    public $share_count;

    public function fields()
    {
        return [
            'id',
            'parent_id',
            'workspace_id',
            'title',
            'body',
            'is_folder',
            'depth',
            'image_path',
            'share_count' => function () {
                return $this->getShareCount();
            },
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
            'short_description' => function ($model) {
                $length = 240;
                $model->depth == 0 ?: $length = 80;
                return StringHelper::truncate(strip_tags($model->body), $length);
            },
            'image_url' => function () {
                return $this->image_path ? Yii::getAlias('@storageUrl' . $this->image_path) : '';
            },
        ];
    }

    /**
     * @return array|string[]
     */
    public function extraFields()
    {
        return ['children', 'workspace', 'createdBy', 'updatedBy', 'articleFiles'];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * Check article and delete if has no sub-articles
     *
     * @return bool|int
     * @throws ValidationException
     */
    public function delete()
    {
        if ($this->getChildren()->count()) {
            throw new ValidationException(Yii::t('app', 'You can\'t delete this article because it has sub-articles'));
        }
        $this->deleteWithChildren();

        return true;
    }

    /**
     * Load for image upload
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this->image = UploadedFile::getInstanceByName('image');

        return parent::load($data, $formName);
    }

    /**
     * Upload image
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\ErrorException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->image) {
            return parent::save($runValidation, $attributeNames);
        }
        if (ModelHelper::isImage($this->image->extension)) {
            ModelHelper::deleteImage($this->image_path);

            $dirPath = '/articles/' . $this->workspace_id;
            $this->image_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

            $fullPath = Yii::getAlias('@storage' . $this->image_path);
            if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
            if (!$this->image->saveAs($fullPath, false)) {
                throw new ValidationException(Yii::t('app', 'File not uploaded'));
            }
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * Get share article count
     *
     * @return bool|int|string|null
     */
    public function getShareCount()
    {
        return $this::find()
            ->byId($this->id)
            ->innerJoin(TimelinePostResource::tableName() . ' t', 't.article_id = ' . $this::tableName() . '.id AND t.action =\'SHARE_ARTICLE\'')
            ->count();
    }
}