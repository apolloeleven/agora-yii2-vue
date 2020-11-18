<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

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
        $query = ArticleResource::find()->byWorkspaceId( Yii::$app->request->get('workspace_id'));
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Get breadcrumb for article view page
     *
     * @param $articleId
     * @return array
     * @throws ValidationException
     */
    public function actionGetBreadCrumb($articleId)
    {
        $article = ArticleResource::find()->byId($articleId)->one();

        if (!$article) {
            throw new ValidationException(Yii::t('app', 'This article not exist'));
        }

        $workspace = $article->workspace;
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

        $breadCrumb[] = [
            'text' => $article->title
        ];

        return $breadCrumb;
    }

    /**
     * Add articles to favourites
     *
     * @return mixed
     * @throws ValidationException
     */
    public function actionAddToFavourites()
    {
        $userModel = Yii::$app->user->identity;

        $userModel->favourites = Json::encode(Yii::$app->request->post(), true);
        if (!$userModel->save()) {
            throw new ValidationException(Yii::t('app', 'Unable to save favourite data'));
        }
        return $this->response(null, 201);
    }

    /**
     * Read favourites data
     *
     * @return string
     */
    public function actionReadFromFavourites()
    {
        $userModel = Yii::$app->user->identity;

        return $userModel->favourites ?: '[]';
    }
}