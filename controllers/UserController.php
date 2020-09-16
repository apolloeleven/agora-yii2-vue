<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\LoginForm;
use app\models\User;
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
            ->where(['email' => $email])
            ->one();

        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user with this email'));
        }

        if ($user->status == User::STATUS_INACTIVE) {
            return $this->validationError(Yii::t('app', 'User is disabled'));
        }

        $passwordResetToken = Yii::$app->security->generateRandomString(16);
        $user->password_reset_token = $passwordResetToken;
        $user->expired_date = time();

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
    public function actionCheckTokenValidate($token)
    {
        if (empty(User::findByPasswordResetToken($token))) {
            return $this->validationError(Yii::t('app', 'This link does not valid'));
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
        $user = User::findOne([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);

        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user'));
        }

        $hash = Yii::$app->getSecurity()->generatePasswordHash($request->post('password'));
        $user->password_hash = $hash;

        if (!$user->save()) {
            return $this->validationError(Yii::t('app', 'Unable to save password'));
        }
    }
}