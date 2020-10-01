<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
use app\modules\v1\workspaces\resources\WorkspaceResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class WorkspaceController
 *
 */
class WorkspaceController extends ActiveController
{
    public $modelClass = WorkspaceResource::class;

    /**
     * Get workspaces by users
     *
     * @return ActiveDataProvider
     */
    public function actionGetUserWorkspaces()
    {
        $query = WorkspaceResource::find()
            ->innerJoinWith(['userWorkspaces'])
            ->andWhere([UserWorkspace::tableName() . '.user_id' => Yii::$app->user->id]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     * Get breadcrumb for workspace view page
     *
     * @return array
     */
    public function actionGetBreadCrumb()
    {
        $request = Yii::$app->request;
        $workspaceId = $request->get('workspaceId');

        $workspace = WorkspaceResource::find()->byId($workspaceId)->one();

        if (!$workspace) {
            return $this->validationError(Yii::t('app', 'This workspace not exist'));
        }

        $breadCrumb[] = [
            'text' => Yii::t('app', 'My Workspaces'),
            'to' => [
                'name' => 'workspace',
            ]
        ];

        $breadCrumb[] = [
            'text' => $workspace->abbreviation ?: $workspace->name,
            'to' => [
                'name' => 'workspace.view',
                'params' => [
                    'id' => $workspace->id
                ]
            ]
        ];

        return $breadCrumb;
    }
}