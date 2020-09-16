<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\LoginForm;
use app\models\User;
use app\models\UserProfile;
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
            return Controller::response(Yii::t('app', 'Unable to find user with this email'));
        }

        if ($user->status == User::STATUS_INACTIVE) {
            return Controller::response(Yii::t('app', 'User is disabled'));
        }

        $newPassword = Yii::$app->security->generateRandomString(8);
        $hash = Yii::$app->getSecurity()->generatePasswordHash($newPassword);
        $user->password_hash = $hash;

        if (!$user->save()) {
            return Controller::response(Yii::t('app', 'Unable to save user'));
        }

        MailHelper::resetPassword($user, $newPassword);
    }

    /**
     * Drop Down options for frontend
     *
     * @return array
     */
    public function actionGetDropdownOptions()
    {
        $userProfiles = UserProfile::find()->active()->all();

        $countries = [];
        $jobTitles = [];
        $specialTasks = [];
        $languages = [];
        $departments = [];
        $expertises = [];
        $roles = [];

        foreach ($userProfiles as $profile) {

            if ($profile->department_position != null) {
                foreach ($profile->department_position as $departmentPosition) {
                    if (isset($departmentPosition['department'])) {
                        $departments = array_merge($departmentPosition['department'], $departments);
                    }
                    if (isset($departmentPosition['country'])) {
                        $countries[] = $departmentPosition['country'];
                    }
                    if (isset($departmentPosition['job_title'])) {
                        $jobTitles[] = $departmentPosition['job_title'];
                    }
                }
            }

            if ($profile->languages != null) {
                foreach ($profile->languages as $language) {
                    if ($language != null) {
                        $languages [] = $language;
                    }
                }
            }

            if ($profile->expertise != null) {
                foreach ($profile->expertise as $expert) {
                    if ($expert != null) {
                        $expertises [] = $expert;
                    }
                }
            }

            if ($profile->special_tasks != null) {
                foreach ($profile->special_tasks as $task) {
                    if ($task != null) {
                        $specialTasks[] = $task;
                    }
                }
            }

//            if (isset($profile->user_id)) {
//                $roleNames = Yii::$app->authManager->getRoles();
//                $roles = [];
//                foreach ($roleNames as $key => $role) {
//                    $roles[] = $key;
//                }
//            }

        }

        return [
            'countries' => array_values(array_unique($countries)),
            'jobTitles' => array_values(array_unique($jobTitles)),
            'specialTasks' => array_values(array_unique($specialTasks)),
            'languages' => array_values(array_unique($languages)),
            'departments' => array_values(array_unique($departments)),
            'expertises' => array_values(array_unique($expertises)),
            'roles' => array_values(array_unique($roles)),
        ];
    }
}