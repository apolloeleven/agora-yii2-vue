<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Poll;
use yii\db\ActiveQuery;

/**
 * Class PollResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class PollResource extends Poll
{
    public function fields()
    {
        return [
            'id',
            'question',
            'description',
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
        ];
    }

    public function extraFields()
    {
        return ['createdBy', 'pollAnswers'];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }
}