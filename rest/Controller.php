<?php

namespace app\rest;

use Yii;
use yii\filters\Cors;

class Controller extends \yii\rest\Controller
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
    public static function response($message, $statusCode = 422)
    {
        Yii::$app->response->setStatusCode($statusCode);
        return $message;
    }
}