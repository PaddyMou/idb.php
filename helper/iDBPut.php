<?php
/**
 * Description of iDBPut.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-22
 * Time: 下午12:13
 */
 
class iDBPut extends iDBBase{
    

    public function __construct($aDir , $aKey ) {
        parent::__construct($aDir , $aKey);
    }

    public function save($aValue) {
        $this->deleteDirIfTypeDifferent($this->getDataType($aValue));
        return $this->execute($this->_aDir , $this->_aKey , $aValue);
    }

    private function saveBool($aDir , $aKey ,$aValue) {
        $dir = $this->getDirByKey($aDir , $aKey);
        $val = $aValue ? 1 : 0;
        return $this->writeTypeAndValue($dir , $val , iDBConfig::IDB_TYPE_BOOL);
    }

    private function saveInteger($aDir , $aKey ,$aValue) {
        $dir = $this->getDirByKey($aDir , $aKey);
        return $this->writeTypeAndValue($dir , $aValue , iDBConfig::IDB_TYPE_INTEGER);
    }

    private function saveString($aDir , $aKey ,$aValue) {
        $dir = $this->getDirByKey($aDir , $aKey);
        return $this->writeTypeAndValue($dir , $aValue , iDBConfig::IDB_TYPE_STRING);
    }

    private function saveArray($aDir , $aKey ,$aValue) {
        $dir = $this->getDirByKey($aDir , $aKey);
        $fields = array_keys($aValue);
        $this->writeTypeAndFields($dir , $fields , iDBConfig::IDB_TYPE_ARRAY);
        foreach($aValue as $key => $val) {
            $this->execute($dir , $key , $val);
        }
        return true;
    }

    private function execute($aDir , $aKey ,$aValue) {
        switch ($this->getDataType($aValue)) {
            case iDBConfig::IDB_TYPE_ARRAY:
                return $this->saveArray($aDir , $aKey , $aValue);
            case iDBConfig::IDB_TYPE_BOOL:
                return $this->saveBool($aDir , $aKey , $aValue);
            case iDBConfig::IDB_TYPE_INTEGER:
                return $this->saveInteger($aDir , $aKey , $aValue);
            case iDBConfig::IDB_TYPE_STRING:
                return $this->saveString($aDir , $aKey , $aValue);
            default:
                return $this->saveString($aDir , $aKey , "");
        };
    }
    
}
