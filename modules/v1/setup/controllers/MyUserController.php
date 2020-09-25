<?php

namespace app\modules\v1\setup\controllers;

use app\modules\v1\setup\models\UserProfile;
use app\modules\v1\setup\resources\UserResource;
use app\rest\ActiveController;
use Yii;

class MyUserController extends ActiveController
{
    public $modelClass = UserResource::class;

    /**
     *
     * @author Levan Gogoladze <levanma98@gmail.com>
     */
    public function actionUpdateProfile()
    {

        $request = Yii::$app->request->post();
        $user = Yii::$app->user->identity;
        $userProfile = $user->userProfile;

        $dbTransaction = Yii::$app->db->beginTransaction();
        $user->email = $request['email'];
        if ($request['password'] and $request['password'] != '') {
            $user->password_hash = Yii::$app->security->generatePasswordHash($request['password']);
        }


//        if($user->save() and $userProfile->load() and $userProfile->save()) {
//            $dbTransaction->commit();
//            return $userProfile;
//        }
        $userProfile->first_name = $request['first_name'];
        $userProfile->last_name = $request['last_name'];
        $userProfile->phone = $request['phone'];
        $userProfile->mobile = $request['mobile'];
        $userProfile->birthday = $request['birthday'];
        $userProfile->about_me = $request['about_me'];
        $userProfile->hobbies = $request['hobbies'];
        $userProfile->image = $request['image'];



        if (!$user->save() || !$userProfile->save()) {
            $dbTransaction->rollBack();
            return $this->validationError(Yii::t('app', 'Unable to update user profile'));
        }
        $dbTransaction->commit();
        return $userProfile;

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
}