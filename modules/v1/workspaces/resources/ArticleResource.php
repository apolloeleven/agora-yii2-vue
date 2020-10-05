<?php


namespace app\modules\v1\workspaces\resources;


use app\rest\ValidationException;
use Yii;
use app\modules\v1\workspaces\models\Article;
use yii\db\Exception;
use yii\helpers\StringHelper;

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
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
            'short_description' => function ($model) {
                $length = 240;
                $model->depth == 0 ?: $length = 80;
                return StringHelper::truncate(strip_tags($model->body), $length);
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
}