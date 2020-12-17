<?php


namespace app\modules\v1\workspaces\workspaceBehaviours;

use app\modules\v1\workspaces\models\TimelinePost;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class UrlAnchorBehaviour extends Behavior
{
    public $attributes = [];

    public function events()
    {
        if (!empty($this->attributes)) {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeInsert',
            ];
        }
        return [];
    }

    public function beforeInsert()
    {
        // Find all links which is not
        foreach ($this->attributes as $attribute) {
            $this->owner->{$attribute} = preg_replace("/(?<!(href|\ssrc)=\")(https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*))/", '<a href="$2" target="_blank">$2</a>', $this->owner->{$attribute});
        }
    }
}