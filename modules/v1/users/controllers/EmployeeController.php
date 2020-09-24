<?php
/**
 * Created By Nika Gelashvili
 * Date: 23.09.20
 * Time: 17:25
 */

namespace app\modules\v1\users\controllers;


use app\modules\v1\setup\resources\CountryResource;
use app\modules\v1\setup\resources\DepartmentResource;
use app\modules\v1\users\resources\UserDepartmentResource;
use app\modules\v1\users\resources\UserRoleResource;
use app\rest\ActiveController;
use app\modules\v1\users\models\search\UserDepartmentSearch;
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
        $userDepartmentSearch = new UserDepartmentSearch();
        return $userDepartmentSearch->search(\Yii::$app->request->get());
    }

    public function actionUpdate()
    {

    }

    public function actionView()
    {
        $departmentOptions = DepartmentResource::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $roleOptions = UserRoleResource::getUserRoles();

        $countryOptions = CountryResource::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $positionOptions = UserDepartmentResource::find()
            ->select(['id', 'position', 'country_id'])
            ->distinct()
            ->asArray()
            ->all();

        return [
            'userRoles' => $roleOptions,
            'userPositions' => $positionOptions,
            'countries' => $countryOptions,
            'departments' => $departmentOptions
        ];
    }
}