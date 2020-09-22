<?php


namespace app\rest;


use Exception;
use yii\web\HttpException;

class ValidationException extends HttpException
{
    /**
     * ValidationException constructor.
     * @param null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(422, $message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('app', 'Validation Exception');
    }
}