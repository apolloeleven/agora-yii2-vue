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
use app\modules\v1\users\models\search\UserDepartmentSearch;
use app\rest\ValidationException;
use yii\data\ActiveDataProvider;
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
        unset($actions['update'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['get-dropdown'] = ['GET', 'HEAD', 'OPTIONS'];

        return $verbs;
    }

    public function prepareDataProvider()
    {
        $searchModel = new UserDepartmentSearch();
        return $searchModel->search(\Yii::$app->request->get());
    }

    /**
     * @return array
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function actionUpdate()
    {
        $request = \Yii::$app->request;
        $dbTransaction = \Yii::$app->db->beginTransaction();

        $user = UserResource::find()
            ->byId($request->post('id'))
            ->with(['userDepartments'])
            ->one();

        if (!$user) {
            $dbTransaction->rollBack();
            throw new ValidationException(\Yii::t('app', 'Invalid params provided'));
        }

        if (!$user->load($request->post(), '') || !$user->save()) {
            $dbTransaction->rollBack();
            return $this->validationError($user->getFirstErrors());
        }

        $user->updateRoles($request->post('roles'));
        $user->updateUserDepartments($request->post('userDepartments'), $dbTransaction);

        $dbTransaction->commit();
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
}