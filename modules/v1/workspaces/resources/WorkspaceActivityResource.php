<?php


namespace app\modules\v1\workspaces\resources;


use app\modules\v1\users\resources\UserResource;
use app\modules\v1\workspaces\models\WorkspaceActivity;
use yii\helpers\Json;

class WorkspaceActivityResource extends WorkspaceActivity
{
    public function fields()
    {
        return array_merge(parent::fields(), [
            'data' => function($model) { return Json::decode($model->data); },
            'createdBy'
        ]);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }
}