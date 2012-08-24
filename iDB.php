<?php
/**
 * Description of iDB.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-21
 * Time: 下午2:31
 */

require "helper/iDBConfig.php";
require "helper/iDBFileUtils.php";
require "helper/iDBBase.php";
require "helper/iDBPut.php";
require "helper/iDBGet.php";
require "helper/iDBDelete.php";

class iDB {

    private $_path = "";
    
    public function __construct($aPath) {
        $this->_path = $aPath;
    }

    public function put($aKey , $aValue) {
        $put = new iDBPut($this->_path , $aKey);
        return $put->save($aValue);
    }

    public function get($aKey) {
        $get = new iDBGet($this->_path , $aKey);
        return $get->value();
    }

    public function delete($aKey) {
        $delete = new iDBDelete($this->_path , $aKey);
        return $delete->save();
    }
    
}
