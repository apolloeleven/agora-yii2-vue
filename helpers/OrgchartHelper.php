<?php
/**
 * Created by PhpStorm.
 * User: sai
 * Date: 03.11.20
 * Time: 19:09
 * @author Saiat Kalbiev <kalbievich11@gmail.com>
 */

namespace app\helpers;


use app\modules\v1\setup\resources\DepartmentResource;
use yii\data\ActiveDataProvider;

class OrgchartHelper
{
    /**
     * @param $countryId
     * @return array|mixed
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getCharData($countryId)
    {
        $query = DepartmentResource::find()
            ->byCountryId($countryId);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $departments = $dataProvider->getModels();

        if (!$departments) {
            return [];
        }

        $preparedData = $this->prepareDataBeforeTreeBuild($departments);

        return $this->buildTree($preparedData)[0];
    }

    /**
     * @param $departments DepartmentResource[]
     * @return array
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function prepareDataBeforeTreeBuild($departments)
    {
        $data = [];

        foreach ($departments as $department) {
            $tmpDept = [
                'id' => $department->id,
                'name' => $department->name,
                // Tree build methods expects 0 to be root, therefore leave logic
                'parent_id' => $department->parent_id !== null ? $department->parent_id : 0,
                'employees' => []
            ];

            foreach ($department->userDepartments as $userDepartment) {
                $tmpDept['employees'][] = [
                    'name' => $userDepartment->user->getDisplayName(),
                    'imgSource' => $userDepartment->user->getImageUrl(),
                    'surname' => $userDepartment->position,
                ];
            }

            $data[] = $tmpDept;
        }

        return $data;
    }

    /**
     * @param $items
     * @return mixed
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    private function buildTree($items)
    {
        $children = [];

        foreach ($items as &$item) $children[$item['parent_id']][] = &$item;
        unset($item);

        foreach ($items as &$item) if (isset($children[$item['id']]))
            $item['children'] = $children[$item['id']];

        return $children[0];
    }
}