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
 */
class UserSearch extends User
{
    /**
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'keyword', 'name', 'email', 'phone'
        ]);
    }

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [['keyword', 'name', 'email', 'phone'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = User::find();

        $query
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

        return $dataProvider;
    }
}