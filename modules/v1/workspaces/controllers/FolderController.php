<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\FolderResource;
use app\rest\ActiveController;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class FolderController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class FolderController extends ActiveController
{
    public $modelClass = FolderResource::class;

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

        $query = FolderResource::find()->byWorkspaceId($workspaceId)->roots();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $folderId = $request->post('article_id');
        $workspaceId = $request->post('workspace_id');
        $isFile = $request->post('isFile');

        $parentFolder = null;
        if ($folderId) {
            $parentFolder = FolderResource::findOne($folderId);
            if (!$parentFolder) {
                return $this->validationError(Yii::t('app', 'Unable to find parent folder'));
            }
            $workspaceId = $parentFolder->workspace_id;
        }

        $folder = new FolderResource();

        $folder->name = $request->post('name');
        $folder->body = $request->post('body');
        $folder->workspace_id = $workspaceId;
        $folder->is_file = $isFile ? 1 : 0;

        if ((!$folder->load($request->post(), '')) && !$folder->validate()) {
            return $this->validationError($folder->getFirstErrors());
        }

        if (!$folderId) {
            if (!$folder->makeRoot()) {
                $this->validationError($folder->getFirstErrors());
            }
        } else {
            $folder->parent_id = $folderId;
            if (!$folder->appendTo($parentFolder)) {
                $this->validationError($folder->getFirstErrors());
            }
        }

        return $this->response($folder, 201);
    }

    /**
     * Get folders by parent
     *
     * @param $folderId
     * @return ActiveDataProvider
     */
    public function actionByParent($folderId)
    {
        $query = FolderResource::find()->byParentId($folderId);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}