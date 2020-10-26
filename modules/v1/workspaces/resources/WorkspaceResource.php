<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
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
            'folder_in_folder' => function () {
                return !!$this->folder_in_folder;
            },
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
        return ['createdBy', 'updatedBy'];
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
     * After save workspace create new user workspace
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
        $this->folder_in_folder = $this->folder_in_folder ? 1 : 0;

        if (!$this->image) {
            return parent::save($runValidation, $attributeNames);
        }
        if ($this->isImage()) {
            $this->deleteImage();
        }
        $dirPath = '/workspace/' . $this->id;
        $this->image_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

        $parentSave = parent::save($runValidation, $attributeNames);
        if (!$parentSave) return $parentSave;

        $fullPath = Yii::getAlias('@storage' . $this->image_path);
        if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
        $this->image->saveAs($fullPath);

        return $parentSave;
    }

    /**
     * Check workspace and delete if has no children
     *
     * @return bool|int
     * @throws ValidationException
     * @throws Exception
     */
    public function delete()
    {
        if ($this->getArticles()->count()) {
            throw new ValidationException(Yii::t('app', 'You can\'t delete this workspace because it has folders'));
        }
        $dbTransaction = Yii::$app->db->beginTransaction();
        if (!UserWorkspace::deleteAll(['workspace_id' => $this->id])) {
            $dbTransaction->rollBack();
            return false;
        }
        $dbTransaction->commit();
        return true;
    }
}