<?php

namespace app\modules\v1\users\controllers;

use app\helpers\MailHelper;
use app\modules\v1\users\models\LoginForm;
use app\modules\v1\users\models\SignupForm;
use app\modules\v1\users\models\User;
use app\modules\v1\setup\resources\InvitationResource;
use app\rest\Controller;
use Yii;
use yii\base\Exception;

/**
 * Class AuthController
 *
 * @package app\controllers
 */
class AuthController extends Controller
{
    public function actionLogin()
    {
        $request = Yii::$app->request;
        $model = new LoginForm();

        if (!$model->load($request->post(), '') || !$model->validate() || !$model->login()) {
            return $this->validationError($model->getFirstErrors());
        }
        return $model->getActiveUser()->getApiData();
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
     *
     * @return array
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionSignup()
    {
        $request = Yii::$app->request;

        $invitation = InvitationResource::findByToken($request->post('token'));
        if (!$invitation) {
            return $this->validationError(Yii::t('app', 'Registration link is invalid or expired'));
        }

        $model = new SignupForm();
        if (!$model->load($request->post(), '') || !$model->validate()) {
            return $this->validationError($model->getFirstErrors());
        }

        // This method does not require check for success because it throws exception on error
        $model->signup($invitation);
    }
}