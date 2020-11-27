<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\models\WorkspaceActivity;
use yii\data\ActiveDataProvider;

class WorkspaceActivityController extends \yii\rest\ActiveController
{
    public $modelClass = WorkspaceActivity::class;

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