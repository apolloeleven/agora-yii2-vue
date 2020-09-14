<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\rest\Controller;
use Yii;
use yii\filters\Cors;

/**
 * Class UserController
 *
 * @package app\controllers
 */
class UserController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $model = new LoginForm();

        /** @var User $user */
        $user = $model->getUserByUsername($request->post('username'));

        if (!$model->load($request->post(), '') || !$user || !$model->login()) {
            return Controller::validationError($model->getFirstErrors());
        }

        return $user->getApiData();
    }
}