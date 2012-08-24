<?php
/**
 * Description of FileUtils.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-21
 * Time: 下午4:24
 */
 
class iDBFileUtils {

    public static function createDirIfNotExists($aDir) {
        if (is_dir($aDir) || mkdir($aDir , 0777 , true)) {
            return true;
        }
        return false;
    }


    public static function deleteDir($aDir) {
        $dir = $aDir;
        if (self::isEmptyDir($dir)) {
            rmdir($dir);
        } else {
            if ($dp = opendir($dir)) {
                while($file = readdir($dp)) {
                    $ff = $dir . DIRECTORY_SEPARATOR .$file;
                    if ($file != "." && $file != "..") {
                        if (is_dir($ff)) {
                            self::deleteDir($ff);
                        } else if(is_file($ff)){
                            unlink($ff);
                        }
                    }
                }
                closedir($dp);
                rmdir($dir);
            }
        }
        return false == is_dir($dir);
    }

    public static function writeFile($aDir , $aFileName , $aValue , &$aError = null) {
        if (false == self::createDirIfNotExists($aDir)) {
            return false;
        }
        $filename = $aDir . DIRECTORY_SEPARATOR .  $aFileName;
        return file_put_contents($filename , $aValue) ? true :false;
    }

    public static function getFileContent($aDir , $aFileName) {
        $filename = $aDir . DIRECTORY_SEPARATOR .  $aFileName;
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        return null;
    }

    private static function isEmptyDir($aDir) {
        $dp = opendir($aDir);
        $has_file = true;
        while($file = readdir($dp)) {
            if ($file != "." && $file != "..") {
                $has_file = false;
                break;
            }
        }
        closedir($dp);
        return $has_file;
    }



}
