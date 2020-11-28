<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\PollAnswerResource;
use app\modules\v1\workspaces\resources\PollResource;
use app\modules\v1\workspaces\resources\UserPollAnswerResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

/**
 * Class PollController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class PollController extends ActiveController
{
    public $modelClass = PollResource::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = PollResource::find()->byWorkspaceId(Yii::$app->request->get('workspace_id'));
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     *
     *
     * @return array|mixed
     * @throws Exception
     */
    public function actionAddVote()
    {
        $request = Yii::$app->request;
        $answerIds = $request->post('answerIds');

        $answers = PollAnswerResource::find()->byId($answerIds)->all();

        if (!$answers) {
            return $this->validationError(Yii::t('app', 'Unable to find answers'));
        }

        $dbTransaction = Yii::$app->db->beginTransaction();

        $userAnswerData = [];

        foreach ($answers as $answer) {
            $userAnswerData [] = [
                'poll_answer_id' => $answer->id,
                'pool_id' => $answer->poll_id,
                'created_at' => time(),
                'updated_at' => time(),
                'created_by' => Yii::$app->user->id,
                'updated_by' => Yii::$app->user->id,
            ];
        }
        $createdData = Yii::$app->db->createCommand()
            ->batchInsert
            (
                UserPollAnswerResource::tableName(),
                ['poll_answer_id', 'poll_id', 'created_at', 'updated_at', 'created_by', 'updated_by'],
                $userAnswerData
            )
            ->execute();

        if ($createdData !== count($userAnswerData)) {
            $dbTransaction->rollBack();
            return $this->validationError(Yii::t('app', 'Unable to save vote'));
        }

        $dbTransaction->commit();
        return $this->response($userAnswerData, 201);
    }
}