<?php


namespace app\modules\v1\workspaces\controllers;


use app\modules\v1\workspaces\resources\ArticleFileResource;
use app\modules\v1\workspaces\resources\ArticleResource;
use app\rest\ActiveController;
use app\rest\ValidationException;
use Yii;
use yii\console\Response;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
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
            throw new ValidationException(Yii::t('app', 'Article not found'));
        }

        $files = UploadedFile::getInstancesByName('files');
        if (!$files) {
            throw new ValidationException(Yii::t('app', 'Unable to find files'));
        }

        $dbTransaction = Yii::$app->db->beginTransaction();

        $attachments = [];

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
                throw new ValidationException(Yii::t('app', 'Unable to upload attachment'));
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

        if (!$articleFile->load($request->post(), '') || !$articleFile->save()) {
            throw new ValidationException(Yii::t('app', 'Unable to update label'));
        }

        return $this->response($articleFile);
    }

    /**
     * Delete attachments
     *
     * @return mixed
     * @throws ValidationException
     */
    public function actionDeleteAttachments()
    {
        $request = Yii::$app->request;
        $fileIds = $request->post('fileIds');

        if (count($fileIds) !== ArticleFileResource::deleteAll(['id' => $fileIds])) {
            throw new ValidationException(Yii::t('app', 'Unable to delete attachments'));
        }

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

        return Yii::$app->response->sendFile($articleFile->getFullPath(), $articleFile->getFileName());
    }
}