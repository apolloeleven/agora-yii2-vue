<?php
/**
 * Created By Nika Gelashvili
 * Date: 30.09.20
 * Time: 13:26
 */

namespace app\modules\v1\workspaces\models\search;


use app\modules\v1\workspaces\models\WorkspaceTimelinePost;
use app\modules\v1\workspaces\resources\TimelinePostResource;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TimelinePostSearch extends TimelinePostResource
{
    public $workspaceIds;

    public function attributes()
    {
        return ArrayHelper::merge(array_keys(parent::attributeLabels()), ['workspaceIds']);
    }


    public function rules()
    {
//        return parent::rules();
        return ArrayHelper::merge(parent::rules(), [['workspaceIds'], 'safe']);
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TimelinePostResource::find()
            ->with('workspaceTimelinePosts');

        if ($this->workspaceIds) {
            $query->andWhere([WorkspaceTimelinePost::tableName() . 'workspace_id' => $this->workspaceIds]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}