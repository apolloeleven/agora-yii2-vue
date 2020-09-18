<?php


namespace app\controllers;


use app\models\Invitation;
use Yii;
use app\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * Class InvitationController
 *
 * @package app\controllers
 */
class InvitationController extends ActiveController
{
    public $modelClass = Invitation::class;

    /**
     * {@inheritDoc}
     *
     * @return array|array[]
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'delete'],
                    //TODO roles
                ]
            ]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['update'], $actions['view']);

        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        return new ActiveDataProvider([
            'query' => Invitation::find()->orderBy('status')
        ]);
    }

    /**
     * Get invited user's email by token
     *
     * @param $token
     * @return mixed|null
     */
    public function actionGetEmail($token)
    {
        $invitation = Invitation::findByToken($token);
        if (!$invitation) {
            return $this->validationError(Yii::t('app', 'Invitation token is invalid or expired'));
        }

        return $invitation->email;
    }
}