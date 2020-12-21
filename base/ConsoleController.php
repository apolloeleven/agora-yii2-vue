<?php
/**
 * User: Zura
 * Date: 12/21/2020
 * Time: 9:13 PM
 */

namespace app\base;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class ConsoleController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\base
 */
class ConsoleController extends Controller
{
    public string $dateFormat = 'Y-m-d H:i:s';

    protected function log($message)
    {
        Console::output(date($this->dateFormat) . ' - ' . $message);
    }

    protected function error($message)
    {
        Console::error(date($this->dateFormat) . ' - ' . $message);
    }
}