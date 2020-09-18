<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 19:43
 */

namespace app\rest;


use Yii;
use yii\filters\Cors;

/**
 * Trait ControllerTrait
 * This must be used in \yii\rest\Controller class
 *
 * @package app\rest
 */
trait ControllerTrait
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        return $behaviors;
    }

    /**
     * @param $message
     * @param $statusCode
     * @return array
     */
    public function validationError($message, $statusCode = 422)
    {
        return $this->response($message, $statusCode);
    }

    /**
     * @param     $data
     * @param int $statusCode
     * @return mixed
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function response($data, $statusCode = 200)
    {
        Yii::$app->response->statusCode = $statusCode;
        return $data;
    }
}