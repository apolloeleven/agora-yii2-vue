<?php

namespace app\modules\v1\setup\controllers;

use app\models\ChangePassword;
use app\modules\v1\setup\resources\UserResource;
use app\rest\ActiveController;
use Yii;
use yii\web\UploadedFile;

class MyUserController extends ActiveController
{
    public $modelClass = UserResource::class;


    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['profile'] = ['GET', 'HEAD', 'OPTIONS'];
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
        /** @var $user UserResource */
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
}