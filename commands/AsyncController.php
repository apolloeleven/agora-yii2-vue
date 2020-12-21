<?php
/**
 * User: Zura
 * Date: 12/21/2020
 * Time: 8:27 PM
 */

namespace app\commands;


use app\base\ConsoleController;
use app\modules\v1\workspaces\models\Folder;
use WebPConvert\WebPConvert;
use Yii;
use yii\console\ExitCode;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Class AsyncController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\commands
 */
class AsyncController extends ConsoleController
{
    public function actionProcessImage($folderId)
    {
        $this->log("Select Folder with ID=$folderId");
        $folder = Folder::findOne($folderId);
        if (!$folder) {
            $this->error("File/Folder does not exit with ID = $folderId");
            \Yii::error("File/Folder does not exit with ID = $folderId. ");

            return ExitCode::UNSPECIFIED_ERROR;
        }
        // Convert image into webp
        $src = Yii::getAlias("@storage").$folder->file_path;
        $this->log("Start processing of the file \"$src\"");

        $newFilePath = pathinfo($folder->file_path, PATHINFO_DIRNAME) . '/' . pathinfo($folder->file_path, PATHINFO_FILENAME) . '.webp';
        $destination = Yii::getAlias("@storage{$newFilePath}");


        $this->log("Start fixing the image orientation");
        $this->imageFixOrientation($image, $src);
        $this->log("Finish fixing the image orientation");

        $this->log("Start converting file into webp");
        WebPConvert::convert($src, $destination);
        $this->log("Finish converting file into webp");

        $folder->data = Json::encode([
            'timeline' => [
                'name' => pathinfo($folder->name, PATHINFO_FILENAME) . '.webp',
                'path' => $newFilePath,
                'mime' => 'image/webp',
                'size' => filesize($destination)
            ]
        ]);

        if (!$folder->save()) {
            $this->error("Unable to save folder with ID=$folderId. See error log for more information...");
            Yii::error("Unable to save folder. Data: ".VarDumper::dumpAsString($folder->toArray()).'. Errors: '.VarDumper::dumpAsString($folder->errors));
        }
        $this->log("Saved file/folder with ID=$folderId");
        return ExitCode::OK;
    }

    /**
     *
     *
     * https://stackoverflow.com/questions/7489742/php-read-exif-data-and-adjust-orientation/21797668
     *
     * @param $image
     * @param $filename
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    private function imageFixOrientation(&$image, $filename)
    {
        $image = imagecreatefromstring(file_get_contents($filename));
        $exif = exif_read_data($filename);

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;

                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;

                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }

            imagejpeg($image, $filename);
        }
    }
}