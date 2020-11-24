<?php


namespace app\modules\v1\workspaces\workspaceBehaviours;

use app\modules\v1\workspaces\models\TimelinePost;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class TimelineUrlBehaviour extends Behavior
{
    public $timelinePost;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterInsert',
        ];
    }

    public function afterInsert() {
        if(strlen($this->timelinePost->description !== 0)) {
            $description = $this->timelinePost->description;
            $description = strip_tags(preg_replace("((<\/)\w+(>))", "\n", $description));
            $descriptions = preg_split("/[\n\s,]+/", $description);

            $regex = "/^(http|https):\/\/[^\s]+/";
            foreach ($descriptions as $i) {
                if(preg_match($regex, $i)) {
                    $this->timelinePost->description = str_replace($i, "<a href=\"$i\" target=\"_blank\">$i</a>" ,$this->timelinePost->description);
                }
            }
        }
    }
}