<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\Article;
use app\rest\ValidationException;
use Yii;
use yii\db\Exception;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

/**
 * Class ArticleResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class ArticleResource extends Article
{
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
        return ['children', 'workspace', 'createdBy', 'updatedBy'];
    }

    /**
     * Check article and delete if has no sub-articles
     *
     * @return bool|int
     * @throws ValidationException
     * @throws Exception
     */
    public function delete()
    {
        if ($this->getChildren()->count()) {
            throw new ValidationException(Yii::t('app', 'You can\'t delete this article because it has sub-articles'));
        }
        $dbTransaction = Yii::$app->db->beginTransaction();
        if (!$this->deleteWithChildren()) {
            $dbTransaction->rollBack();
            return false;
        }
        $dbTransaction->commit();
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
        if ($this->isImage()) {
            $this->deleteImage();
        }
        $dirPath = '/articles/' . $this->workspace_id;
        $this->image_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

        $fullPath = Yii::getAlias('@storage' . $this->image_path);
        if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
        if (!$this->image->saveAs($fullPath, false)) {
            throw new ValidationException(Yii::t('app', 'File not uploaded'));
        }

        return parent::save($runValidation, $attributeNames);
    }
}