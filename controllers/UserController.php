<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use app\models\UserProfile;
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

    /**
     *
     * @return UserProfile|array
     * @author Levan Gogoladze <levanma98@gmail.com>
     */
    public function actionGetProfile($id)
    {
        $model = new UserProfile();
        $profile = $model->getProfile($id);
        if (!$profile) {
            return $this->validationError(Yii::t('app', 'Unable to find user profile with this id'));
        }
        return $profile;
    }

    /**
     *
     * @author Levan Gogoladze <levanma98@gmail.com>
     */
    public function actionUpdateProfile() {
        $request = Yii::$app->request->post();

//        $user = Yii::$app->user->identity;
//        return $user;
        $user = User::findOne($request['id']);
        $userProfile = $user->userProfile;
        $user->email = $request['email'];

        if($request['password'] and $request['password'] != '') {
            $user->password = Yii::$app->security->generatePasswordHash($request['password']);
        }

        $userProfile->firstname = $request['firstName'];
        $userProfile->lastName = $request['lastName'];
        $userProfile->phone = $request['phone'];
        $userProfile->mobile = $request['mobile'];
        $userProfile->birthday = $request['birthday'];
        $userProfile->aboutMe = $request['aboutMe'];
        $userProfile->hobbies = $request['hobbies'];


        return Yii::$app->user->identity->email;
    }
}