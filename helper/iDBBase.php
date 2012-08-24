<?php
/**
 * Description of iDBBase.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-22
 * Time: 下午12:16
 */
 
class iDBBase {

    protected $_aDir;

    protected $_aKey;

    private $_error;

    private $_storeInFile = true;

    private $_storeInXattr = true;

    public function __construct($_aDir , $aKey) {
        $this->_aDir = $_aDir;
        $this->_aKey = trim($aKey);
        $this->_storeInFile = iDBConfig::STORE_IN_FILE;
        $this->_storeInXattr = iDBConfig::STORE_IN_XATTR;
    }

    public function storeInFile($status) {
        $this->_storeInFile = $status;
    }

    public function storeInXattr($status) {
        $this->_storeInXattr = $status;
    }



    protected function deleteDirIfTypeDifferent($aType) {
        if (iDBConfig::FORCE_UPDATE) {
            $file_type = iDBFileUtils::getFileContent($this->_aDir , iDBConfig::IDB_KEY_TYPE_NAME);
            if ($file_type && $file_type != $aType && ($aType == iDBConfig::IDB_TYPE_ARRAY || $file_type == iDBConfig::IDB_TYPE_ARRAY)) {
                iDBFileUtils::deleteDir($this->_aDir);
            }
        }
    }

    protected function getDataType($aValue) {
        if ($aValue && is_array($aValue)) {
            return iDBConfig::IDB_TYPE_ARRAY;
        }elseif (is_bool($aValue)) {
            return iDBConfig::IDB_TYPE_BOOL;
        } elseif (is_numeric($aValue)) {
            return iDBConfig::IDB_TYPE_INTEGER;
        } elseif (is_string($aValue)) {
            return iDBConfig::IDB_TYPE_STRING;
        }
        return iDBConfig::IDB_TYPE_STRING;
    }

    protected function writeTypeAndFields($aDir , $aFields , $aType) {
        $storeFile = $storeXattr = true;
        if ($this->_storeInFile) {
            $storeFile = iDBFileUtils::writeFile($aDir , iDBConfig::IDB_FIELDS_NAME , join(iDBConfig::KEYS_SEPARATION,$aFields)) &&
                            iDBFileUtils::writeFile($aDir , iDBConfig::IDB_KEY_TYPE_NAME , $aType);
            if (false == $storeFile) {
                $this->setErrorMessage("Please check {$aDir} is writeable");
            }
        }
        if ($this->_storeInXattr) {
            iDBFileUtils::createDirIfNotExists($aDir);
            $storeXattr = xattr_set($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_FIELDS_NAME , join(iDBConfig::KEYS_SEPARATION,$aFields)) &&
                            xattr_set($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_KEY_TYPE_NAME , $aType);
            if (false == $storeXattr) {
                $this->setErrorMessage("Please check thie user permissions.vim /etc/fstab find '/' and add user_attr");
            }
        }
        return $storeFile && $storeXattr;
    }

    protected function writeTypeAndValue($aDir , $aValue ,$aType) {
        $storeFile = $storeXattr = true;
        if ($this->_storeInFile) {
            $storeFile = iDBFileUtils::writeFile($aDir , iDBConfig::IDB_VALUE_NAME , $aValue) && iDBFileUtils::writeFile($aDir , iDBConfig::IDB_KEY_TYPE_NAME , $aType);
            if (false == $storeFile) {
                $this->setErrorMessage("Please check {$aDir} is writeable");
            }
        }
        if ($this->_storeInXattr) {
            iDBFileUtils::createDirIfNotExists($aDir);
            $storeXattr = xattr_set($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_VALUE_NAME , $aValue) && xattr_set($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_KEY_TYPE_NAME , $aType);
            if (false == $storeXattr) {
                $this->setErrorMessage("Please check thie user permissions.vim /etc/fstab find '/' and add user_attr");
            }
        }
        return $storeFile && $storeXattr;
    }

    protected function readType($aDir , $aKey) {
        $dir = $this->getDirByKey($aDir , $aKey);
        if ($this->_storeInXattr) {
            $type = $this->readTypeFromAttr($dir);
            $type = $type ? $type : $this->readTypeFromFile($dir);
            return $type;
        }
        return $this->readTypeFromFile($dir);
    }

    protected function readKeys($aDir , $aKey) {
        $dir = $this->getDirByKey($aDir , $aKey);
        $fields = "";
        if ($this->_storeInXattr) {
            $fields = $this->readKeysFromAttr($dir);
            $fields = $fields ? $fields : $this->readKeysFromFile($dir);
        } else {
            $fields = $this->readKeysFromFile($dir);
        }
        return explode(iDBConfig::KEYS_SEPARATION,$fields);
    }

    protected function readValue($aDir , $aKey , $aType) {
        $dir = $this->getDirByKey($aDir , $aKey);
        if ($this->_storeInXattr) {
            $value = $this->readValueFromAttr($dir);
            $value = $value ? $value : $this->readValueFromFile($dir);
        } else {
            $value = $this->readValueFromFile($dir);
        }
        return $this->valueFormat($value ,$aType);
    }

    private function readKeysFromFile($aDir) {
        return iDBFileUtils::getFileContent($aDir , iDBConfig::IDB_FIELDS_NAME);
    }

    private function readKeysFromAttr($aDir) {
        return xattr_get($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_FIELDS_NAME);
    }


    private function readTypeFromFile($aDir) {
        return iDBFileUtils::getFileContent($aDir , iDBConfig::IDB_KEY_TYPE_NAME);
    }

    private function readTypeFromAttr($aDir) {
        return xattr_get($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_KEY_TYPE_NAME);
    }

    private function readValueFromFile($aDir) {
        return iDBFileUtils::getFileContent($aDir , iDBConfig::IDB_VALUE_NAME);
    }

    private function readValueFromAttr($aDir) {
        return xattr_get($aDir , iDBConfig::IDB_KEY_PREFIX . iDBConfig::IDB_VALUE_NAME);
    }

    private function valueFormat($aValue , $aType) {
        switch ($aType) {
            case iDBConfig::IDB_TYPE_BOOL:
                return $aValue ? true : false;
            case iDBConfig::IDB_TYPE_STRING:
                return $aValue;
            case iDBConfig::IDB_TYPE_INTEGER:
                return floatval($aValue);
        }
    }

    protected function setErrorMessage($message) {
        $this->_error = $message;
    }

    protected function getDirByKey($dir ,$key) {
        return $dir . DIRECTORY_SEPARATOR . $key;
    }

}
