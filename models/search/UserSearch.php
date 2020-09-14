<?php

namespace app\models\search;

use app\models\User;
use app\models\UserProfile;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class UserSearch
 *
 * @package app\models\search
 *
 * @property int $keyword
 * @property int $name
 * @property int $email
 * @property int $phone
 * @property int $jobTitles
 * @property int $countries
 * @property int $specialTasks
 * @property int $expertise
 * @property int $language
 */
class UserSearch extends User
{
    /**
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'keyword', 'name', 'email', 'phone', 'jobTitles', 'countries', 'specialTasks',
            'expertise', 'language'
        ]);
    }

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [['keyword', 'name', 'email', 'phone', 'jobTitles', 'countries', 'specialTasks',
                'expertise', 'language'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = User::find();

        $query
            ->select([
                User::tableName() . '.*',
                'job_title_extracted' => 'JSON_EXTRACT(' . UserProfile::tableName() . ".department_position, '$[*].job_title')",
                'country_extracted' => 'JSON_EXTRACT(' . UserProfile::tableName() . ".department_position, '$[*].country')"
            ])
            ->joinWith('userProfile')
            ->distinct()
            ->notDeleted();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ],
                'attributes' => [
                    'email',
                    'created_at',
                    'country' => [
                        'asc' => ['country_extracted' => SORT_ASC],
                        'desc' => ['country_extracted' => SORT_DESC],
                    ],
                    'job_title' => [
                        'asc' => ['job_title_extracted' => SORT_ASC],
                        'desc' => ['job_title_extracted' => SORT_DESC],
                    ],
                    'status',
                    'name' => [
                        'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                        'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                        'label' => 'name',
                    ],
                ]
            ],
        ]);

        if (!$this->load($params, '') || !$this->validate()) {
            return $dataProvider;
        }

        if ($this->keyword) {
            $query
                ->andFilterWhere([
                    'OR',
                    ['like', 'first_name', $this->keyword],
                    ['like', 'last_name', $this->keyword],
                    ['like', 'email', $this->keyword],
                    ['like', 'phone', $this->keyword],
                    ['like', 'username', $this->keyword],
                    ['like', 'hometown', $this->keyword],
                    ['like', 'special_tasks', $this->keyword],
                    ['like', 'job_title', $this->keyword],
                    ['like', 'department_position', $this->keyword],
                    ['like', 'country', $this->keyword],
                    ['like', 'area_director', $this->keyword],
                    ['like', 'expertise', $this->keyword],
                    ['like', 'languages', $this->keyword]
                ]);
        }
        if ($this->name) {
            $query->andfilterWhere([
                'OR',
                ['like', 'first_name', $this->name],
                ['like', 'last_name', $this->name]
            ]);
        }
        if ($this->email) {
            $query->andfilterWhere(['like', 'email', $this->email]);
        }
        if ($this->phone) {
            $query->andfilterWhere([
                'like',
                "TRIM(REPLACE(phone, '-', ''))",
                trim(str_replace('-', '', $this->phone))
            ]);
        }
        if ($this->jobTitles) {
            $query->andfilterWhere([
                'OR',
                ['OR like', 'job_title', $this->jobTitles],
                ['OR like', 'department_position', $this->jobTitles],
            ]);
        }
        if ($this->countries) {
            $query->andfilterWhere([
                'OR',
                ['OR like', 'country', $this->countries],
                ['OR like', 'department_position', $this->countries],
            ]);
        }
        if ($this->specialTasks) {
            $query->andfilterWhere(['OR like', 'special_tasks', $this->specialTasks]);
        }
        if ($this->expertise) {
            $query->andfilterWhere(['OR like', 'expertise', $this->expertise]);
        }
        if ($this->language) {
            $query->andfilterWhere(['OR like', 'languages', $this->language]);
        }

        return $dataProvider;
    }
}