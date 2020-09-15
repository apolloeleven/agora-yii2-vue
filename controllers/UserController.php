<?php

namespace app\controllers;

use app\models\LoginForm;
use app\rest\Controller;
use Yii;

/**
 * Class UserController
 *
 * @package app\controllers
 */
class UserController extends Controller
{

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $model = new LoginForm();

        if (!$model->load($request->post(), '') || !$model->validate() || !$model->login()) {
            return $this->validationError($model->getFirstErrors());
        }
        return $model->getUser()->getApiData();
    }
}