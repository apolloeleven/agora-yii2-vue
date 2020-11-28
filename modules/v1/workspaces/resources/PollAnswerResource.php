<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\workspaces\models\PollAnswer;
use yii\db\ActiveQuery;

/**
 * Class PollAnswerResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class PollAnswerResource extends PollAnswer
{
    public function extraFields()
    {
        return ['userPollAnswers'];
    }

    /**
     * Gets query for [[UserPolls]].
     *
     * @return ActiveQuery
     */
    public function getUserPolls()
    {
        return $this->hasMany(UserPollAnswerResource::class, ['poll_answer_id' => 'id']);
    }
}