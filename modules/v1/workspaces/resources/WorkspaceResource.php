<?php


namespace app\modules\v1\workspaces\resources;


use app\helpers\ModelHelper;
use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\Article;
use app\modules\v1\workspaces\models\Folder;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class WorkspaceResource
 *
 * @package app\modules\v1\workspaces\resources
 */
class WorkspaceResource extends Workspace
{
    public function fields()
    {
        return [
            'id',
            'name',
            'abbreviation',
            'description',
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'updated_at' => function () {
                return $this->updated_at * 1000;
            },
            'image_url' => function () {
                return $this->image_path ? Yii::getAlias('@storageUrl' . $this->image_path) : '';
            },
        ];
    }

    /**
     * @return array|string[]
     */
    public function extraFields()
    {
        return ['createdBy', 'updatedBy', 'articles', 'rootFolder'];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'updated_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTimelinePosts()
    {
        return $this->hasMany(TimelinePostResource::class, ['workspace_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRootFolder()
    {
        return $this->hasOne(FolderResource::class, ['workspace_id' => 'id'])
            ->andWhere(['depth' => 0]);
    }

    /**
     * After save workspace create new user workspace and default folder for timeline posts
     *
     * @param $insert
     * @param $changedAttributes
     * @throws ValidationException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $userWorkspace = new UserWorkspace();
            $userWorkspace->workspace_id = $this->id;
            $userWorkspace->user_id = Yii::$app->user->id;
            $userWorkspace->role = UserResource::ROLE_ADMIN;

            if (!$userWorkspace->save()) {
                throw new ValidationException(Yii::t('app', 'Unable to create user workspace'));
            }

            $workspaceRootFolder = new FolderResource();
            $workspaceRootFolder->name = 'Files';
            $workspaceRootFolder->workspace_id = $this->id;

            if (!$workspaceRootFolder->makeRoot()) {
                throw new ValidationException(Yii::t('app', 'Unable to create root folder'));
            }

            $childFolder = new FolderResource();
            $childFolder->workspace_id = $this->id;
            $childFolder->is_timeline_folder = 1;
            $childFolder->parent_id = $workspaceRootFolder->id;
            $childFolder->name = "$this->name - Timeline posts";

            if (!$childFolder->appendTo($workspaceRootFolder)) {
                throw new ValidationException(Yii::t('app', 'Unable to create child folder'));
            }
        }
    }

    /**
     * Load for image upload
     *
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this->image = UploadedFile::getInstanceByName('image');

        return parent::load($data, $formName);
    }

    /**
     * Upload image
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\ErrorException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->image) {
            return parent::save($runValidation, $attributeNames);
        }
        if (ModelHelper::isImage($this->image->extension)) {
            ModelHelper::deleteImage($this->image_path);

            $this->image_path = '/workspace/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

            $fullPath = Yii::getAlias('@storage' . $this->image_path);
            if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
            if (!$this->image->saveAs($fullPath, false)) {
                throw new ValidationException(Yii::t('app', 'File not uploaded'));
            }
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * Check workspace and delete if has no children
     *
     * @return bool|int
     * @throws ValidationException
     */
    public function delete()
    {
        $dbTransaction = Yii::$app->db->beginTransaction();

        $timelinePosts = $this->getTimelinePosts()->all();
        foreach ($timelinePosts as $timelinePost) {
            $timelinePost->delete();
        }
        if ($this->getTimelinePosts()->count()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Can't delete timeline posts"));
        }

        $folder = $this->getRootFolder()->one();
        $folder->deleteWithChildren();
        if ($folder->getChildren()->count()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Can't delete workspace folders"));
        }

        UserWorkspace::deleteAll(['workspace_id' => $this->id]);
        if (UserWorkspace::find()->where(['workspace_id' => $this->id])->count()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Can't delete workspace users"));
        }

        Article::deleteAll(['workspace_id' => $this->id]);
        if (Article::find()->where(['workspace_id' => $this->id])->count()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Can't delete workspace articles"));
        }

        Workspace::deleteAll(['id' => $this->id]);
        if (Workspace::find()->where(['id' => $this->id])->count()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Can't delete workspace"));
        }

        if ($this->image_path){
            $fullPath = dirname(Yii::getAlias('@storage' . $this->image_path));
            if (is_dir($fullPath)) {
                FileHelper::removeDirectory($fullPath);
            }
        }

        $path = "/file-manager/{$this->id}";
        $fullPath = Yii::getAlias('@storage' . $path);
        if (is_dir($fullPath)) {
            FileHelper::removeDirectory($fullPath);
        }

        $dbTransaction->commit();

        return true;
    }
}