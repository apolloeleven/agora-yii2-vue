<?php

namespace app\controllers;

use app\helpers\MailHelper;
use app\models\search\UserSearch;
use app\models\User;
use app\models\UserProfile;
use app\rest\ActiveController;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * Class MyUserController
 *
 * @package app\controllers
 */
class MyUserController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        //TODO rules
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        //TODO rules
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        //TODO rules
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        //TODO rules
                    ]
                ]
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['view']);

        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        $userSearchModel = new UserSearch();

        return $userSearchModel->search(Yii::$app->getRequest()->getQueryParams());
    }

    /**
     * User create action
     *
     * @return mixed
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function actionCreate()
    {
        $requestData = Yii::$app->getRequest()->getBodyParams();

        /** Check if requestDate has all required fields */
        if (!$requestData || !isset($requestData['userProfile'])) {
            return $this->validationError(Yii::t('app', 'Unable to find all required fields'), 400);
        }

        $userModel = new User();
        $userProfileModel = new UserProfile();

        if (!array_key_exists('departmentPosition', $requestData)) {
            $requestData['departmentPosition'] = null;
        }

        $requestData['userProfile']['department_position'] = $requestData['departmentPosition'];

        /** load $requestDate in $userProfileModel and $userModel models separately, that fits requestData format*/
        if (!$userProfileModel->load($requestData['userProfile'], '')) {
            return $this->validationError($userProfileModel->errors);
        }
        if (!$userModel->load($requestData, '')) {
            return $this->validationError($userModel->errors);
        };

        $userModel->username = $userModel->email;
        //Change 'Generate random password' to already generate password
        $password = ArrayHelper::getValue($requestData, 'password');

        /** create password_hash to save it in database*/
        $userModel->setPassword($password);

        $userModel->status = User::STATUS_ACTIVE;

        return $this->saveData($userModel, $userProfileModel, true, $password);
    }

    /**
     * User update action
     *
     * @param $id
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $requestData = Yii::$app->getRequest()->getBodyParams();

        // Check if requestDate is has all required fields
        if (!$requestData || !isset($requestData['userProfile']) || !$id) {
            return $this->validationError(Yii::t('app', 'Unable to find all required fields'), 400);
        }

        /* @var $userModel User */
        /* @var $userProfileModel UserProfile */

        $userModel = User::find()
            ->notDeleted()
            ->byId($id)
            ->one();
        if (!$userModel) {
            return $this->validationError(Yii::t('app', 'Unable to find user'));
        }

        $userProfileModel = $userModel->userProfile;

        if (!array_key_exists('departmentPosition', $requestData)) {
            $requestData['departmentPosition'] = null;
        }

        $requestData['userProfile']['department_position'] = $requestData['departmentPosition'];

        if (!$userProfileModel->load($requestData['userProfile'], '')) {
            return $this->validationError($userProfileModel->errors);
        }
        if (!$userModel->load($requestData, '')) {
            return $this->validationError($userModel->errors);
        };

        // If image removed
        if (array_key_exists('image', $requestData)) {
            $userProfileModel->image_deleted = true;
        }

        // create password_hash to save it in database
        $newPassword = ArrayHelper::getValue($requestData, 'password');
        if ($newPassword) {
            $userModel->setPassword($newPassword);
        }

        return $this->saveData($userModel, $userProfileModel, false, $newPassword);
    }

    /**
     * @param User $userModel
     * @param UserProfile $userProfileModel
     * @param $insert
     * @param $password
     * @return mixed
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    private function saveData($userModel, $userProfileModel, $insert, $password)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if (!$userModel->save()) {
            $transaction->rollBack();
            return $this->validationError($userModel->errors);
        }

        if ($insert) {
            $userProfileModel->user_id = $userModel->id;

            if (!$userProfileModel->save()) {
                $transaction->rollBack();
                return $this->validationError($userProfileModel->errors);
            }
        }

        if (isset($password)) {
            MailHelper::generatedPassword($userModel, $password);
        }

        $transaction->commit();

        return $insert ?
            $this->response(Yii::t('app', 'Successfully created'), 201) :
            $this->response(Yii::t('app', 'Successfully updated'), 200);
    }

    /**
     * User delete action
     *
     * @param $id
     * @return mixed
     * @throws \yii\db\Exception
     * @throws ErrorException
     */
    public function actionDelete($id)
    {
        /** @var User $user */
        $user = User::find()->active()->byId($id)->one();
        if (!$user) {
            return $this->validationError(Yii::t('app', 'Unable to find user'));
        }

        $transaction = Yii::$app->db->beginTransaction();

        $imagePath = $user->userProfile->image_path;
        if ($imagePath !== null && $imagePath !== "") {
            $dir = dirname($imagePath);
            FileHelper::removeDirectory(Yii::getAlias("@storage" . $dir));
        }

        $userProfileData = $user->userProfile;
        $userProfileData->first_name = '[DELETED]';
        $userProfileData->last_name = '[DELETED]';
        $userProfileData->phone = '[DELETED]';
        $userProfileData->mobile = '[DELETED]';
        $userProfileData->birthday = null;
        $userProfileData->hometown = '[DELETED]';
        $userProfileData->special_tasks = null;
        $userProfileData->about_me = '[DELETED]';
        $userProfileData->languages = null;
        $userProfileData->expertise = null;
        $userProfileData->department = null;
        $userProfileData->area_director = null;
        $userProfileData->position = null;
        $userProfileData->image_path = null;
        if (!$userProfileData->save()) {
            $transaction->rollBack();
            return $this->validationError($userProfileData->errors);
        }

        if (!$user->markDeleted()->save()) {
            $transaction->rollBack();
            return $this->validationError(array_merge($user->errors, $user->userProfile->errors));
        };

        $transaction->commit();

        return $this->response(Yii::t('app', 'Successfully deleted'), 204);
    }

    /**
     * User view action
     *
     * @param $id
     * @return User|array
     */
    public function actionView($id)
    {
        if (!$id) {
            return $this->validationError(Yii::t('app', 'Missing required parameter'), 400);
        }

        $model = User::find()->byId($id)->one();

        if (!$model) {
            return $this->validationError(Yii::t('app', 'Unable to find user'));
        }

        return $model;
    }

}