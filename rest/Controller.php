<?php

namespace app\rest;

use Yii;

class Controller extends \yii\rest\Controller
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @param $message
     * @param $statusCode
     * @return array
     */
    public static function response($message, $statusCode = 422)
    {
        Yii::$app->response->setStatusCode($statusCode);
        return $message;
    }
}