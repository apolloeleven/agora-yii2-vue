<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
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
        unset($actions['create']);

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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $articleId = $request->post('article_id');
        $workspaceId = $request->post('workspace_id');
        $isArticle = $request->post('isArticle');

        $parentFolder = null;
        if ($articleId) {
            $parentFolder = ArticleResource::findOne($articleId);
            if (!$parentFolder) {
                return $this->validationError($parentFolder->getFirstErrors());
            }
            $workspaceId = $parentFolder->workspace_id;
        }

        $article = new ArticleResource();

        $article->title = $request->post('title');
        $article->body = $request->post('body');
        $article->workspace_id = $workspaceId;
        $article->is_folder = $isArticle ? 0 : 1;

        if ((!$article->load($request->post(), '')) && !$article->validate()) {
            return $this->validationError($article->getFirstErrors());
        }

        if (!$articleId) {
            if (!$article->makeRoot()) {
                $this->validationError($article->getFirstErrors());
            }
        } else {
            $article->parent_id = $articleId;
            if (!$article->appendTo($parentFolder)) {
                $this->validationError($article->getFirstErrors());
            }
        }

        return $this->response($article, 201);
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

        $parents = $article->parents()->all();

        foreach ($parents as $parent) {
            $breadCrumb[] = [
                'text' => $parent->title,
                'to' => [
                    'name' => 'article.view',
                    'params' => [
                        'id' => $parent->id
                    ]
                ]
            ];
        }
        $breadCrumb[] = [
            'text' => $article->title
        ];

        return $breadCrumb;
    }

    /**
     * Get articles by parent
     *
     * @param $articleId
     * @return ActiveDataProvider
     */
    public function actionByParent($articleId)
    {
        $query = ArticleResource::find()->byParentId($articleId);

        return new ActiveDataProvider([
            'query' => $query
        ]);
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

        $userModel->favourites = json_encode(Yii::$app->request->post(), true);
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