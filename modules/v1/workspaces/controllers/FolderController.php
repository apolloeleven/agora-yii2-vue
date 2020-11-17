<?php


namespace app\modules\v1\workspaces\controllers;

use app\modules\v1\workspaces\resources\FolderResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\console\Response;
use yii\web\UploadedFile;

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

    /**
     *
     *
     * @return array
     * @throws ValidationException
     */
    public function prepareDataProvider()
    {
        $parentId = Yii::$app->request->get('parent_id');

        $data = FolderResource::find()->byParentId($parentId)->hasNoTimelineFile()->all();
        $defaultFolder = FolderResource::find()->byId($parentId)->isTimelineFolder()->one();
        if ($defaultFolder) {
            $data = FolderResource::find()->hasTimelineFile()->byWorkspaceId($defaultFolder->workspace_id)->all();
        }

        $folder = FolderResource::findOne(['id' => $parentId]);
        if (!$folder) {
            throw new ValidationException(Yii::t('app', 'This folder does not exist'));
        }
        $breadcrumbData = $folder->parents()->all();

        return [
            'data' => $data,
            'currentFolder' => $folder,
            'breadcrumbData' => $breadcrumbData
        ];
    }

    /**
     * @return array|mixed
     * @throws ValidationException
     * @throws Exception
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $folderId = $request->post('folder_id');
        $isFile = $request->post('isFile');

        $parentFolder = FolderResource::findOne($folderId);
        if (!$parentFolder) {
            return $this->validationError(Yii::t('app', 'Unable to find parent folder'));
        }
        $workspaceId = $parentFolder->workspace_id;


        if (!$isFile) {
            $folder = new FolderResource();
            $folder->name = $request->post('name');
            $folder->workspace_id = $workspaceId;
            $folder->is_file = 0;

            if ((!$folder->load($request->post(), '')) || !$folder->validate()) {
                return $this->validationError($folder->getFirstErrors());
            }

            $folder->parent_id = $folderId;
            if (!$folder->appendTo($parentFolder)) {
                return $this->validationError($folder->getFirstErrors());
            }

            return $this->response($folder, 201);
        } else {
            $attachFiles = UploadedFile::getInstancesByName('files');
            if (!$attachFiles) {
                throw new ValidationException(Yii::t('app', 'Unable to find files'));
            }

            $attachments = [];

            foreach ($attachFiles as $attachFile) {
                $folder = FolderResource::find()
                    ->byParentId($folderId)
                    ->byName($attachFile->name)
                    ->isFile()
                    ->one();

                // if exist same name file overwrite
                if (!$folder) {
                    $folder = new FolderResource();
                    $folder->is_file = 1;
                    $folder->workspace_id = $workspaceId;
                }

                $folder->uploadFile($attachFile, $workspaceId);

                if ($folder->id && !$folder->save()) {
                    return $this->validationError($folder->getFirstErrors());
                } else {
                    $folder->parent_id = $folderId;
                    if (!$folder->appendTo($parentFolder)) {
                        return $this->validationError($folder->getFirstErrors());
                    }
                }
                $attachments[] = $folder;
            }
            return $this->response($attachments, 201);
        }
    }

    /**
     * Get attachment config data
     *
     * @return mixed
     */
    public function actionGetAttachConfig()
    {
        $phpConfig = [
            'upload_max_filesize' => ['title' => Yii::t('app', 'Max file size'), 'size' => ini_get('upload_max_filesize')],
            'max_file_uploads' => ['title' => Yii::t('app', 'Max file uploads'), 'size' => ini_get('max_file_uploads')],
        ];

        return $this->response($phpConfig);
    }

    /**
     *
     *
     * @return void
     * @throws ValidationException
     * @throws \yii\db\Exception
     */
    public function actionDeleteFolders()
    {
        $request = Yii::$app->request;
        $fileIds = $request->post('fileIds');

        $dbTransaction = Yii::$app->db->beginTransaction();
        foreach ($fileIds as $id) {
            $folder = FolderResource::findOne(['id' => $id]);
            if ($folder->getChildren()->count()) {
                $dbTransaction->rollBack();
                throw new ValidationException(Yii::t('app', 'You can\'t delete this folder because it has sub-folders or files'));
            }
            $folder->deleteWithChildren();
        }
        $dbTransaction->commit();
    }

    /**
     * Download file
     *
     * @param $id
     * @return Response|\yii\web\Response
     * @throws ValidationException
     */
    public function actionDownloadFile($id)
    {
        $file = FolderResource::findOne(['id' => $id]);

        if (!$file) {
            throw new ValidationException(Yii::t('app', 'File does not exist'));
        }

        return Yii::$app->response->sendFile($file->getFullPath(), $file->name);
    }
}