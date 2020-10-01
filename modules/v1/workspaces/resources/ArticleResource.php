<?php


namespace app\modules\v1\workspaces\resources;


use Yii;
use app\modules\v1\workspaces\models\Article;
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
     * Delete article with children
     *
     * @return bool|int
     */
    public function delete()
    {
        if (!$this->deleteWithChildren()) {
            return false;
        }
        return true;
    }
}