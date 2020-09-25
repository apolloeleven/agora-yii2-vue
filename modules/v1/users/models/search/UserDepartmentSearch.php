<?php

namespace app\modules\v1\users\models\search;

use app\modules\v1\users\resources\UserDepartmentResource;
use app\modules\v1\users\resources\UserResource;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Created By Nika Gelashvili
 * Date: 24.09.20
 * Time: 12:53
 */
class UserDepartmentSearch extends UserDepartmentResource
{
    public function attributes()
    {
        return parent::attributes();
    }

    public function rules()
    {
        return parent::rules();
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserResource::find()
            ->with([
                'userDepartments',
                'userDepartments.department',
                'userDepartments.department.country'
            ])
            ->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}