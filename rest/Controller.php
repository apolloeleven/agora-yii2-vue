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

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'login' => ['options', 'post'],
                'send-password-reset-link' => ['options', 'post'],
                'check-token-validity' => ['options', 'get'],
                'password-reset' => ['options', 'post'],
            ]
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