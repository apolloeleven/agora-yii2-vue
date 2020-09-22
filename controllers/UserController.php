<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\LoginForm;
use app\models\User;
use app\models\UserProfile;
use app\rest\Controller;
use Yii;
use yii\base\Exception;

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

    /**
     * Send email password reset link
     *
     * @return array|bool
     * @throws Exception
     */
    public function actionSendPasswordResetLink()
    {
        $request = Yii::$app->request;
        $email = $request->post('email');

        $user = User::find()
            ->byEmail($email)
            ->one();

        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user with this email'));
        }

        if ($user->isInactive()) {
            return $this->validationError(Yii::t('app', 'User is disabled'));
        }

        $passwordResetToken = Yii::$app->security->generateRandomString(16);
        $user->password_reset_token = $passwordResetToken;
        $user->expire_date = time();

        if (!$user->save()) {
            return $this->validationError(Yii::t('app', 'Unable to save user'));
        }

        if (!MailHelper::resetPassword($user)) {
            return $this->validationError(Yii::t('app', 'Unable to send email'));
        };
    }

    /**
     * Get password reset token and check validate
     *
     * @param $token
     * @return array
     */
    public function actionCheckTokenValidity($token)
    {
        if (!User::findByPasswordResetToken($token)) {
            return $this->validationError(Yii::t('app', 'Password reset link is invalid or expired'));
        }
    }

    /**
     * Reset password
     *
     * @return array|bool
     * @throws Exception
     */
    public function actionPasswordReset()
    {
        $request = Yii::$app->request;

        $token = $request->post('token');
        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user'));
        }

        $hash = Yii::$app->getSecurity()->generatePasswordHash($request->post('password'));
        $user->password_hash = $hash;

        if (!$user->save()) {
            return $this->validationError(Yii::t('app', 'Unable to save password'));
        }
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