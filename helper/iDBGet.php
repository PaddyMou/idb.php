<?php
/**
 * Description of iDBGet.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-22
 * Time: 下午3:55
 */
 
class iDBGet extends iDBBase{

    public function __construct($aDir , $aKey ) {
        parent::__construct($aDir , $aKey);
    }

    public function value() {
        $this->execute($this->_aDir , $this->_aKey , $result);
        return $result;
    }

    private function execute($aDir , $aKey , &$result) {
        $type = $this->readType($aDir , $aKey);
        if ($type != iDBConfig::IDB_TYPE_ARRAY) {
            $result = $this->readValue($aDir , $aKey , $type);
        } else {
            if ($result && iDBConfig::LAZY_LOAD) {
                //$result =
            }
            return $this->executeArray($aDir , $aKey , $result);
        }
    }

    private function executeArray($aDir , $aKey , &$result) {
        $keys = $this->readKeys($aDir , $aKey);
        for($i=0; $i<count($keys) ;$i++) {
            $dir = $aDir . DIRECTORY_SEPARATOR . $aKey;
            $key = $keys[$i];
            $this->execute($dir , $key , $result[$key]);
        }
    }

}
