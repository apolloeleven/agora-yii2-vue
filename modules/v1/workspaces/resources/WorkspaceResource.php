<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
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
            'folder_in_folder',
            'created_at',
            'updated_at',
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
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->image) {
            return parent::save($runValidation, $attributeNames);
        }
        $dirPath = '/workspace' . $this->id;
        $this->image_path = $dirPath . '/' . Yii::$app->security->generateRandomString() . '/' . $this->image->name;

        $parentSave = parent::save($runValidation, $attributeNames);
        if (!$parentSave) return $parentSave;

        $fullPath = Yii::getAlias('@storage' . $this->image_path);
        if (!is_dir(dirname($fullPath))) FileHelper::createDirectory(dirname($fullPath));
        $this->image->saveAs($fullPath);

        return $parentSave;
    }
}