<?php


namespace app\modules\v1\workspaces\behaviors;

use app\modules\v1\workspaces\models\WorkspaceActivity;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\Json;

class ActivityBehavior extends Behavior
{
    /**
     * @var \Closure
     */
    public $workspace_id = null;
    /**
     * @var string
     */
    public $contentIdAttribute = 'id';

    public $events = ['create', 'update', 'delete'];
    /**
     * @var string
     */
    public $defaultTemplate = [
        'create' => '{user} created {model} {title}',
        'update' => '{user} updated {model} {title}',
        'delete' => '{user} deleted {model} {title}',
    ];

    public $template = [];

    /**
     * @var \Closure
     */
    public $data = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function appendAction($action)
    {
        $this->template = array_merge($this->defaultTemplate, $this->template);
        $userActivity = new WorkspaceActivity();
        $userActivity->workspace_id = $this->workspace_id instanceof \Closure ? call_user_func($this->workspace_id) : null;
        $userActivity->table_name = $this->owner->tableName();
        $userActivity->content_id = $this->owner->{$this->contentIdAttribute};
        $userActivity->action = $action;
        $userActivity->description = $this->template[$action];
        $userActivity->data = $this->data ? Json::encode(call_user_func($this->data)) : null;
        $saved = $userActivity->save();

        if(!$saved) { //If arguments are invalid, save() fails, but no error is produced. Will reduce time of debugging. You are welcome :)
            throw new Exception("Invalid Arguments");
        }
    }

    public function afterInsert()
    {
        if(in_array(WorkspaceActivity::ACTION_INSERT, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_INSERT);

    }

    public function afterUpdate()
    {
        if(in_array(WorkspaceActivity::ACTION_UPDATE, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_UPDATE);
    }

    public function beforeDelete()
    {
        if(in_array(WorkspaceActivity::ACTION_DELETE, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_DELETE);
    }
}