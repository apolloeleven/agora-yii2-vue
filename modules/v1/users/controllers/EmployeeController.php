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
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class EmployeeController extends ActiveController
{
    public $modelClass = UserDepartmentResource::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['index', 'create', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'create', 'delete'],
                    //TODO roles
                ]
            ]
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['view'], $actions['index']);

        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => UserResource::find()
                ->with(['userDepartments'])
                ->active()
        ]);
    }
}