<?php

namespace app\modules\v1\setup\models\search;

use app\modules\v1\setup\resources\DepartmentResource;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: sai
 * Date: 23.09.20
 * Time: 16:36
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */
class DepartmentSearch extends DepartmentResource
{
    /**
     * @return array|array[]
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function rules()
    {
        return [
            [['id', 'country_id'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider(['query' => $query,]);

        if (!$this->load($params, '') || !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            self::tableName() . '.country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['LIKE', self::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}
