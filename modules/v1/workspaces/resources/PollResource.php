<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Poll;
use app\modules\v1\workspaces\models\PollAnswer;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class PollResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class PollResource extends Poll
{
    public $answers;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [[['answers'], 'safe']]);
    }

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

    /**
     *
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     * @throws ValidationException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();

        if (!$this->question || !$this->description) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', 'Question and description can not be blank'));
        }

        if (count($this->answers) < 2) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', 'Answers must be 2 or more'));
        }
        $parentSave = parent::save($runValidation, $attributeNames);

        $answerData = [];

        foreach ($this->answers as $answer) {
            $answerData [] = [
                'pool_id' => $this->id,
                'answer' => $answer,
                'created_at' => time(),
                'updated_at' => time(),
                'created_by' => Yii::$app->user->id,
                'updated_by' => Yii::$app->user->id,
            ];
        }
        $createdData = Yii::$app->db->createCommand()
            ->batchInsert
            (
                PollAnswer::tableName(),
                ['poll_id', 'answer', 'created_at', 'updated_at', 'created_by', 'updated_by'],
                $answerData
            )
            ->execute();

        if ($createdData !== count($answerData)) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', 'Unable to save answer'));
        }

        $dbTransaction->commit();
        return $parentSave;
    }
}