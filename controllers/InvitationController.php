<?php


namespace app\controllers;


use app\base\BaseController;
use app\models\Invitation;
use app\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;

/**
 * Class InvitationController
 *
 * @package app\controllers
 */
class InvitationController extends BaseController
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

        $behaviors['authenticator']['except'][] = 'get-email';
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
     * Get user email with validate token
     *
     * @param $token
     * @return array|ActiveRecord
     */
    public function actionGetEmail($token)
    {
        $invitation = Invitation::findByToken($token);
        if (!$invitation) {
            return $this->validationError(\Yii::t('app', 'Unable to find valid invitation token'));
        }

        return $invitation->email;
    }
}