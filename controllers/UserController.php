<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\LoginForm;
use app\models\User;
use app\rest\Controller;
use Yii;
use yii\base\Exception;
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
            return Controller::response($model->getFirstErrors());
        }

        return $user->getApiData();
    }

    /**
     * Password reset action.
     *
     * @return array|bool
     * @throws Exception
     */
    public function actionResetPassword()
    {
        $request = Yii::$app->request;
        $email = $request->post('email');

        $user = User::find()
            ->where(['email' => $email])
            ->one();

        if (!$user) {
            return Controller::response(Yii::t('app', 'Unable to find user with this email'), 422);
        }

        if ($user->status == User::STATUS_INACTIVE) {
            return Controller::response(Yii::t('app', 'User is disabled'), 422);
        }

        $newPassword = Yii::$app->security->generateRandomString(8);
        $hash = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
        $user->password_hash = $hash;

        if (!$user->save()) {
            return Controller::response(Yii::t('app', 'Unable to save user'), 422);
        }

        MailHelper::resetPassword($user, $newPassword);
    }
}