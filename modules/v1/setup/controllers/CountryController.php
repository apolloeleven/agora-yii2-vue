<?php
/**
 * User: zura
 * Date: 14.09.20
 * Time: 13:08
 */

namespace app\modules\v1\setup\controllers;


use app\helpers\OrgchartHelper;
use app\modules\v1\setup\resources\CountryResource;
use app\rest\ActiveController;

/**
 * Class CountryController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\setup\controllers
 */
class CountryController extends ActiveController
{
    public $modelClass = CountryResource::class;

    /**
     * @return array|mixed
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function actionOrgChartData()
    {
        $orgchartHelper = new OrgchartHelper();
        return $orgchartHelper->getCharData(\Yii::$app->request->get('country_id'));
    }
}