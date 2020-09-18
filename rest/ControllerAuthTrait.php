<?php
/**
 * User: zura
 * Date: 16.09.20
 * Time: 13:55
 */

namespace app\rest;


use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

/**
 * Class ControllerAuthTrait
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\rest
 */
trait ControllerAuthTrait
{
    use ControllerTrait;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['cors'] = [
            'class' => Cors::class
        ];
        $auth['except'] = ['options'];
        $auth['authMethods'] = [
            HttpBearerAuth::class
        ];

        $behaviors['authenticator'] = $auth;

        return $behaviors;
    }
}