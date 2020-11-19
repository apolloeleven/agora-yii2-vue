<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\models\UserActivity;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

class UserActivityController extends ActiveController
{
    public $modelClass = UserActivity::class;

    public function actions()
    {
        $actions = parent::actions();
        if(\Yii::$app->request->get('workspaceId')) {
            $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        }
        return $actions;
    }

    public function prepareDataProvider() {
        return new ActiveDataProvider([
            'query' => $this->modelClass::find()->andWhere(['workspace_id' => \Yii::$app->request->get('workspaceId')])->orderBy([
                'created_at' => SORT_DESC
            ])
        ]);
    }
}