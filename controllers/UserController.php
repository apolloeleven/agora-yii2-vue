<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\rest\Controller;
use Yii;

/**
 * Class UserController
 *
 * @package app\controllers
 */
class UserController extends Controller
{

    /**
     *
     *
     * @return array
     * @author Levani Khvedelidze <levani19972@gmail.com>
     */
    public function actionLogin()
    {
        $request = Yii::$app->request;
        $model = new LoginForm();

        if (!$model->load($request->post(), '') || !$model->validate() || !$model->login()) {
            return $this->validationError($model->getFirstErrors());
        }
        return $model->getUser()->getApiData();
    }


    /**
     *
     *
     * @return User|array
     * @author Levani Khvedelidze <levani19972@gmail.com>
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $model = new User();

        if (!$model->load($request->post(), '') || !$model->validate()) {
            return $this->validationError($model->getFirstErrors());
        }
        if (!$model->save()) {
            return $this->validationError($model->getFirstErrors());
        }
        return $model;
    }

    /**
     *
     *
     * @author Levani Khvedelidze <levani19972@gmail.com>
     */
    public function actionGetUser()
    {
        return Yii::$app->user;
    }
}