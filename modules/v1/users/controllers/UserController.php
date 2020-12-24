<?php

namespace app\modules\v1\users\controllers;

use app\modules\v1\users\models\ChangePassword;
use app\modules\v1\setup\resources\UserProfileResource;
use app\modules\v1\users\resources\UserResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

class UserController extends ActiveController
{
    public $modelClass = UserProfileResource::class;


    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['profile'] = ['GET', 'HEAD', 'OPTIONS'];
        $verbs['active-users'] = ['GET', 'HEAD', 'OPTIONS'];
        $verbs['update-profile'] = ['PUT', 'OPTIONS'];
        $verbs['change-password'] = ['PUT', 'OPTIONS'];

        return $verbs;
    }

    /**
     *
     * @author Levan Gogoladze <levanma98@gmail.com>
     */
    public function actionUpdateProfile()
    {
        /** @var $user UserProfileResource */
        $user = Yii::$app->user->identity;
        $user->image = UploadedFile::getInstanceByName('image');
        if ($user->load(Yii::$app->request->post(), '') && $user->save()) {
            return $user;
        }

        return $user->getFirstErrors();
    }

    public function actionProfile()
    {
        return Yii::$app->user->identity;
    }

    public function actionChangePassword()
    {
        $model = new ChangePassword();
        $model->user = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post(), '') && $model->validate() && $model->changePassword()) {
            return $this->response(null, 204);
        }

        return $this->validationError($model->getFirstErrors());
    }

    /**
     * Get all users for workspace invitation
     *
     * @return ActiveDataProvider
     */
    public function actionActiveUsers()
    {
        $query = UserResource::find()->active();

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}