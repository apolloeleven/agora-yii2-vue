<?php


namespace app\modules\v1\users\controllers;


use app\helpers\MailHelper;
use app\modules\v1\users\resources\InvitationResource;
use Yii;
use app\rest\ActiveController;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;

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
        $behaviors['authenticator']['except'][] = 'get-email';

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['view']);

        return $actions;
    }

    public function actionCreate()
    {
        $body = Yii::$app->getRequest()->getBodyParams();
        $model = InvitationResource::find()
            ->andWhere([
                'email' => $body['email'],
                'status' => InvitationResource::STATUS_PENDING
            ])
            ->one();
        if ($model) {
            MailHelper::sendInvitation($model);

            return $model;
        }
        $transaction = Yii::$app->db->beginTransaction();
        $model = new InvitationResource();
        if ($model->load($body, '') && $model->save()) {
            MailHelper::sendInvitation($model);

            $transaction->commit();
            return $this->response($model, 201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
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