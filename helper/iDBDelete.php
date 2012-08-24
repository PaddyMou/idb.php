<?php
/**
 * Description of iDBDelete.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-23
 * Time: 下午12:41
 */
 
class iDBDelete extends iDBBase{

    public function __construct($_aDir , $aKey) {
        $this->_aDir = $_aDir;
        $this->_aKey = $aKey;
    }

    public function save() {
        $dir = $this->getDirByKey($this->_aDir , $this->_aKey);
        return iDBFileUtils::deleteDir($dir);
    }

}
