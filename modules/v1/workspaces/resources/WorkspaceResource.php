<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\UserWorkspace;
use app\modules\v1\workspaces\models\Workspace;
use app\rest\ValidationException;
use Yii;
use yii\db\ActiveQuery;

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
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
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
}