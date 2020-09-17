<?php


namespace app\base;


use Yii;
use yii\rest\ActiveController;

class BaseController extends ActiveController
{
    /**
     * {@inheritDoc}
     *
     * @return array|array[]
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count',
                ],
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
     */
    public function response($data, $statusCode = 200)
    {
        Yii::$app->response->statusCode = $statusCode;
        return $data;
    }
}
