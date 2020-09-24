<?php


namespace app\modules\v1\users\controllers;


use app\modules\v1\users\resources\InvitationResource;
use Yii;
use app\rest\ActiveController;
use yii\filters\AccessControl;

/**
 * Class InvitationController
 *
 * @package app\controllers
 */
class InvitationController extends ActiveController
{
    public $modelClass = InvitationResource::class;

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
        unset($actions['update'], $actions['view']);

        return $actions;
    }

    /**
     * Get invited user's email by token
     *
     * @param $token
     * @return mixed|null
     */
    public function actionGetEmail($token)
    {
        $invitation = InvitationResource::findByToken($token);
        if (!$invitation) {
            return $this->validationError(Yii::t('app', 'Invitation token is invalid or expired'));
        }

        return $invitation->email;
    }
}