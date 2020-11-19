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
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate'
        ];
    }

    public function beforeInsert()
    {
        $userActivity = new UserActivity();
        $userActivity->workspace_id = $this->owner->{$this->workspaceIdAttribute};
        $userActivity->table_name = $this->tableNameAttribute;
        $userActivity->content_id = $this->owner->{$this->contentIdAttribute};
        $userActivity->action = $this->owner->{$this->actionAttribute};
        $userActivity->description = strip_tags($this->owner->{$this->descriptionAttribute});
        $userActivity->save();
    }
}