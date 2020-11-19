<?php


namespace app\modules\v1\workspaces\behaviors;

use app\modules\v1\workspaces\models\UserActivity;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ActivityBehavior extends Behavior
{
    public $workspaceIdAttribute;
    public $tableNameAttribute;
    public $contentIdAttribute;
    public $actionAttribute;
    public $descriptionAttribute;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    public function appendAction($action)
    {
        $userActivity = new UserActivity();
        $userActivity->workspace_id = $this->owner->{$this->workspaceIdAttribute};
        $userActivity->table_name = $this->tableNameAttribute;
        $userActivity->content_id = $this->owner->{$this->contentIdAttribute};
        $userActivity->action = $action;
        $userActivity->description = strip_tags($this->owner->{$this->descriptionAttribute});
        $userActivity->save();
    }

    public function afterInsert()
    {
        $this->appendAction('Insert');
    }

    public function afterUpdate()
    {
        $this->appendAction('Update');
    }

    public function beforeDelete()
    {
        $this->appendAction('Delete');
    }
}