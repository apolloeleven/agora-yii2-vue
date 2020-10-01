<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\models\Article;
use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class ArticleController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class ArticleController extends ActiveController
{
    public $modelClass = ArticleResource::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $request = Yii::$app->request;
        $workspaceId = $request->get('workspace_id');

        $query = ArticleResource::find()->byWorkspaceId($workspaceId)->roots();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Get breadcrumb for article view page
     *
     * @return array
     */
    public function actionGetBreadCrumb()
    {
        $request = Yii::$app->request;
        $articleId = $request->get('articleId');

        $article = ArticleResource::find()->byId($articleId)->one();
        $workspace = $article->workspace;

        if (!$article) {
            return $this->validationError(Yii::t('app', 'This article not exist'));
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

        // TODO all article children

        $breadCrumb[] = [
            'text' => $article->title,
            'to' => [
                'name' => 'article.view',
                'params' => [
                    'id' => $article->id
                ]
            ]
        ];

        return $breadCrumb;
    }
}