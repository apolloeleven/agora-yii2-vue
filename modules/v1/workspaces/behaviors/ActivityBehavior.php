<?php


namespace app\modules\v1\workspaces\behaviors;

use app\modules\v1\workspaces\models\UserActivity;
use yii\base\Behavior;
use yii\db\ActiveRecord;
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
    public $description = [
        'create' => '{user} created {model} {title}',
        'update' => '{user} updated {model} {title}',
        'delete' => '{user} created {model} {title}',
    ];
    /**
     * @var \Closure
     */
    public $data = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            UserActivity::EVENT_SHARE => 'onShare'
        ];
    }

    public function appendAction($action)
    {
        $userActivity = new UserActivity();
        $userActivity->workspace_id = $this->workspace_id instanceof \Closure ? call_user_func($this->workspace_id) : null;
        $userActivity->table_name = $this->owner->tableName();
        $userActivity->content_id = $this->owner->{$this->contentIdAttribute};
        $userActivity->action = $action;
        $userActivity->description = $this->description;
        $userActivity->data = $this->data ? Json::encode(call_user_func($this->data)) : null;
        $userActivity->save();
    }

    public function afterInsert()
    {
        $this->appendAction(UserActivity::ACTION_INSERT);
    }

    public function afterUpdate()
    {
        $this->appendAction(UserActivity::ACTION_UPDATE);
    }

    public function afterDelete()
    {
        $this->appendAction(UserActivity::ACTION_DELETE);
    }
}