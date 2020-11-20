<?php
/**
 * Created By Nika Gelashvili
 * Date: 23.09.20
 * Time: 17:25
 */

namespace app\modules\v1\users\controllers;


use app\modules\v1\users\resources\UserDepartmentResource;
use app\modules\v1\users\resources\UserResource;
use app\rest\ActiveController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class EmployeeController extends ActiveController
{
    public $modelClass = UserResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'create', 'delete', 'update'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'delete', 'update'],
                    //TODO roles
                ]
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        return $actions;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['get-dropdown'] = ['GET', 'HEAD', 'OPTIONS'];
        return $verbs;
    }

    public function actionGetDropdown()
    {
        $data = [
            'userRoles' => [],
            'userPositions' => [],
        ];

        $data['userRoles'] = UserResource::getUserRoles();

        $positionOptions = UserDepartmentResource::find()
            ->select(['position'])
            ->distinct()
            ->asArray()
            ->all();

        $data['userPositions'] = ArrayHelper::getColumn($positionOptions, 'position');

        return $data;
    }

    /**
     * Update user status from inline
     *
     * @return array|mixed
     */
    public function actionUpdateStatus()
    {
        $request = Yii::$app->request;
        $userId = $request->post('id');

        $user = UserResource::find()->byId($userId)->one();

        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user'), 422);
        }

        if (!$user->load($request->post(), '') || !$user->save()) {
            return $this->validationError(Yii::t('app', 'Unable to update user'), 422);
        }

        return $this->response(null, 201);
    }
}