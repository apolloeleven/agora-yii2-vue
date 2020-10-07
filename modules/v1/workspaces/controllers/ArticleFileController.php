<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\ArticleFileResource;
use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Throwable;
use Yii;
use yii\console\Response;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

/**
 * Class ArticleFileController
 *
 * @package app\modules\v1\workspaces\controllers
 */
class ArticleFileController extends ActiveController
{
    public $modelClass = ArticleFileResource::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'getFilesByArticle'];
        unset($actions['create'], $actions['update'], $actions['view']);

        return $actions;
    }

    public function getFilesByArticle()
    {
        $request = Yii::$app->request;
        $articleId = $request->get('articleId');

        $query = ArticleFileResource::find()->byArticleId($articleId);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }

    /**
     * Upload files
     *
     * @return mixed
     * @throws Exception
     * @throws ValidationException
     * @throws \yii\base\Exception
     */
    public function actionAttachFiles()
    {
        $request = Yii::$app->request;
        $article = ArticleResource::findOne($request->post('article_id'));

        if (!$article) {
            throw new ValidationException(Yii::t('app', 'This file not exist'));
        }

        $files = UploadedFile::getInstancesByName('files');

        $dbTransaction = Yii::$app->db->beginTransaction();

        $attachments = [];

        if (!$files) {
            throw new ValidationException(Yii::t('app', 'Unable to find files'));
        }
        foreach ($files as $file) {
            $articleFile = ArticleFileResource::find()
                ->byArticleId($article->id)
                ->byName($file->name)
                ->one();

            // if exist same name file overwrite
            if (!$articleFile) {
                $articleFile = new ArticleFileResource();
                $articleFile->article_id = $article->id;
            }

            if (!$articleFile->uploadFile($file)) {
                $dbTransaction->rollBack();
                throw new ValidationException(Yii::t('app', 'Unable to update attachment'));
            }

            // TODO non mp4 videos to be converted into mp4

            if (!$articleFile->save()) {
                $dbTransaction->rollBack();
                throw new ValidationException(Yii::t('app', 'Unable to save attachment'));
            }

            $attachments[] = $articleFile;
        }
        $dbTransaction->commit();

        return $this->response($attachments, 201);
    }

    /**
     * Get attachment config data
     *
     * @return mixed
     */
    public function actionGetAttachConfig()
    {
        $phpConfig = [
            'upload_max_filesize' => ['title' => 'Max file size', 'size' => ini_get('upload_max_filesize')],
            'max_file_uploads' => ['title' => 'Max file uploads', 'size' => ini_get('max_file_uploads')],
        ];

        return $this->response($phpConfig);
    }

    /**
     * Change article file label
     *
     * @return ArticleFileResource|array
     * @throws ValidationException
     */
    public function actionChangeLabel()
    {
        $request = Yii::$app->request;

        $articleFile = ArticleFileResource::findOne(['id' => $request->post('id')]);

        if (!$articleFile) {
            throw new ValidationException(Yii::t('app', 'Article file not exist'));
        }

        $articleFile->label = $request->post('label');
        if (!$articleFile->save()) {
            throw new ValidationException(Yii::t('app', 'Unable to update label'));
        }

        return $this->response($articleFile);
    }

    /**
     * Delete attachments
     *
     * @return mixed
     * @throws ValidationException
     * @throws Throwable
     * @throws Exception
     * @throws StaleObjectException
     */
    public function actionDeleteAttachments()
    {
        $request = Yii::$app->request;

        $fileIds = $request->post('fileIds');
        $articleFiles = ArticleFileResource::find()->byId($fileIds)->all();

        if (!$articleFiles) {
            throw new ValidationException(Yii::t('app', 'Article files not exist'));
        }

        $dbTransaction = Yii::$app->db->beginTransaction();
        foreach ($articleFiles as $articleFile) {
            if ($articleFile->delete() === false) {
                $dbTransaction->rollBack();
                throw new ValidationException(Yii::t('app', 'Unable to delete attachments'));
            }
        }
        $dbTransaction->commit();
        return $this->response(null, 204);
    }

    /**
     * Download attachment
     *
     * @param $id
     * @return Response|\yii\web\Response
     * @throws ValidationException
     */
    public function actionDownloadAttachment($id)
    {
        $articleFile = ArticleFileResource::findOne(['id' => $id]);

        if (!$articleFile) {
            throw new ValidationException(Yii::t('app', 'Article file not exist'));
        }

        $fullPath = Yii::getAlias('@storage' . $articleFile->path);
        $fileName = $articleFile->name;

        if ($articleFile->label) {
            $fileName = $articleFile->label . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        }

        return Yii::$app->response->sendFile($fullPath, $fileName);
    }
}