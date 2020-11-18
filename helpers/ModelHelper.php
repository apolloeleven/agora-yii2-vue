<?php


namespace app\helpers;

use Yii;
use yii\base\ErrorException;
use yii\helpers\FileHelper;

/**
 * Class ModelHelper
 *
 * @package app\helpers
 */
class ModelHelper
{
    /**
     * Check image extension
     *
     * @param $extension
     * @return bool
     */
    public static function isImage($extension)
    {
        return in_array($extension, ['png', 'jpeg', 'svg', 'gif', 'jpg']);
    }

    /**
     * Delete image
     *
     * @param $imagePath
     * @return bool
     * @throws ErrorException
     */
    public static function deleteImage($imagePath)
    {
        if ($imagePath) {
            $dir = dirname($imagePath);
            FileHelper::removeDirectory(Yii::getAlias("@storage/$dir"));
        }

        return true;
    }

    /**
     * Delete file
     *
     * @param $filePath
     * @return bool
     */
    public static function deleteFile($filePath)
    {
        if (!$filePath) {
            return false;
        }
        FileHelper::unlink(Yii::getAlias("@storage/$filePath"));

        return true;
    }
}