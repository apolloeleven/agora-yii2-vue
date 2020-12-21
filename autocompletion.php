<?php
/**
 * User: Zura
 * Date: 12/21/2020
 * Time: 8:36 PM
 */

/**
 * Used for IDE autocompletion only
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|\yii\web\Application|\yii\base\Application|\yii\console\Application
     */
    public static $app;
}

/**
 * Class BaseApplication
 *
 * @property \app\components\AsyncComponent $async
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 */
abstract class BaseApplication extends yii\base\Application
{

}