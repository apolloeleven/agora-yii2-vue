<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\rest\ValidationException;
use Yii;
use app\modules\v1\workspaces\models\Article;
use yii\db\ActiveQuery;
use yii\db\Exception;
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
        ];
    }

    /**
     * @return array|string[]
     */
    public function extraFields()
    {
        return ['children', 'workspace', 'createdBy', 'updatedBy', 'articleFiles', 'articleComments'];
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
     * Gets query for [[ArticleComments]].
     *
     * @return ActiveQuery
     */
    public function getArticleComments()
    {
        return $this->hasMany(UserCommentResource::class, ['article_id' => 'id']);
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