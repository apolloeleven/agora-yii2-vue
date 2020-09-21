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
     * Password reset action.
     *
     * @return array|bool
     * @throws Exception
     * @author Salome Kavtaradze <s_kavtaradze2@cu.edu.ge>
     */
    public function actionResetPassword()
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

        $newPassword = Yii::$app->security->generateRandomString(8);
        $hash = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
        $user->password_hash = $hash;

        if (!$user->save()) {
            return $this->validationError(Yii::t('app', 'Unable to save user'));
        }

        MailHelper::resetPassword($user, $newPassword);
    }
}