<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Article;
use app\modules\v1\workspaces\models\TimelinePost;
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
            'workspace_id',
            'title',
            'body',
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
                return StringHelper::truncate(strip_tags($model->body), 240);
            },
        ];
    }

    /**
     * @return array|string[]
     */
    public function extraFields()
    {
        return [
            'workspace',
            'createdBy',
            'updatedBy',
            'articleComments',
            'userLikes',
            'myLikes',
        ];
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
        return $this->hasMany(UserCommentResource::class, ['article_id' => 'id'])->orderBy('created_at DESC');
    }

    /**
     * Gets query for [[UserLikes]].
     *
     * @return ActiveQuery
     */
    public function getUserLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['article_id' => 'id']);
    }

    /**
     * Get share article count
     *
     * @return bool|int|string|null
     */
    public function getShareCount()
    {
        $timelinePostTb = TimelinePostResource::tableName();
        $tb = $this::tableName();

        return $this::find()
            ->byId($this->id)
            ->innerJoin("$timelinePostTb t", [
                "AND",
                ["t.action" => TimelinePost::ACTION_SHARE_ARTICLE],
                "t.article_id = $tb.id"
            ])
            ->count();
    }

    /**
     * @return ActiveQuery
     */
    public function getMyLikes()
    {
        return $this->hasMany(UserLikeResource::class, ['article_id' => 'id'])
            ->andWhere(['created_by' => Yii::$app->user->id]);
    }
}