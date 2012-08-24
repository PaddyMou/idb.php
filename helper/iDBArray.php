<?php
/**
 * Description of iDBArray.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-23
 * Time: 下午2:00
 */
 
class iDBArray implements ArrayAccess{

    private $_dir;
    private $_key;

    private $container = array();
    
    public function __construct($aDir , $aKey) {
        $this->container = array(
            "one"   => 1,
            "two"   => 2,
            "three" => 3,
        );
    }
    
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }
    
    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }
    
    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

}
