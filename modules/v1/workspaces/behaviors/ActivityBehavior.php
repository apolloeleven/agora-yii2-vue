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
    /**
     * @var string
     */
    public $tableName = null;

    public $events = ['create', 'update', 'delete'];
    /**
     * @var string
     */
    public $defaultTemplate = [
        'create' => 'Created {model} {title}',
        'update' => 'Updated {model} {title}',
        'delete' => 'Deleted {model} {title}',
    ];

    public $template = [];

    /**
     * @var \Closure
     */
    public $data = null;

    /**
     * @var \Closure
     */
    public $parentIdentity = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function prepareTemplate()
    {
        foreach ($this->template as $event => $template) {
            if ($this->template[$event] instanceof \Closure)
                $this->template[$event] = call_user_func($template);
        }
        $this->template = array_merge($this->defaultTemplate, $this->template);
    }

    public function appendAction($action)
    {
        $this->prepareTemplate();
        $workspaceActivity = new WorkspaceActivity();
        $workspaceActivity->workspace_id = $this->workspace_id instanceof \Closure ? call_user_func($this->workspace_id) : null;
        $workspaceActivity->table_name = $this->tableName ?? $this->owner->tableName();
        $workspaceActivity->content_id = $this->owner->{$this->contentIdAttribute};
        $workspaceActivity->action = $action;
        $workspaceActivity->description = $this->template[$action];
        $workspaceActivity->data = $this->data ? Json::encode(call_user_func($this->data)) : null;
        $workspaceActivity->parent_identity = $this->parentIdentity ? Json::encode(call_user_func($this->parentIdentity)) : null;
        $saved = $workspaceActivity->save();

        if (!$saved) { //If arguments are invalid, save() fails, but no error is produced. Will reduce time of debugging. You are welcome :)
            throw new Exception("Invalid Arguments");
        }
    }

    public function afterInsert()
    {
        if (in_array(WorkspaceActivity::ACTION_INSERT, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_INSERT);

    }

    public function afterUpdate()
    {
        if (in_array(WorkspaceActivity::ACTION_UPDATE, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_UPDATE);
    }

    public function beforeDelete()
    {
        if (in_array(WorkspaceActivity::ACTION_DELETE, $this->events))
            $this->appendAction(WorkspaceActivity::ACTION_DELETE);
    }
}