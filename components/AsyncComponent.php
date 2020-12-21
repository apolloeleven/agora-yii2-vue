<?php
/**
 * User: Zura
 * Date: 12/21/2020
 * Time: 8:35 PM
 */

namespace app\components;


/**
 * Class AsyncComponent
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\components
 */
class AsyncComponent extends \yii\base\Component
{
    public string $phpBinary = 'php';

    public function execute($action, ...$args)
    {
        $command = $this->phpBinary . ' ' . \Yii::getAlias('@app/yii') . ' ' . $action . ' ' . implode(" ", $args).' >> /dev/null &';
        \Yii::info("START executing command \"$command\"");
        exec($command, $output);
    }
}