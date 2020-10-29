<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\ArticleFile;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class ArticleFileResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class ArticleFileResource extends ArticleFile
{
    const VIDEO = 'video';

    public function fields()
    {
        return [
            'id',
            'name',
            'label',
            'size',
            'to_process',
            'processing',
            'mime' => function () {
                $mime = explode('/', $this->mime)[0];
                return $mime == self::VIDEO ? $mime : $this->mime;
            },
            'path' => function () {
                return $this->getPublicUrl();
            },
            'created_at' => function () {
                return $this->updated_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
            'content'
        ];
    }

    public function extraFields()
    {
        return ['updatedBy'];
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }

    /**
     * Get public url
     *
     * @return bool|string
     */
    public function getPublicUrl()
    {
        if (explode('/', $this->mime)[0] == self::VIDEO) {
            return $this->path ? Yii::getAlias('@storageUrl' . explode('.', $this->path)[0] . '.mp4') : '';
        } else {
            return $this->path ? Yii::getAlias('@storageUrl' . $this->path) : '';
        }
    }
}