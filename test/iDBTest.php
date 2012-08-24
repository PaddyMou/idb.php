<?php
/**
 * Description of iDBTest.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-23
 * Time: 下午4:42
 */

require "../iDB.php";

class iDBTest extends PHPUnit_Framework_TestCase{

    private $_path ;

    public function setUp() {
        $this->_path = __DIR__ . "/../idb_test";
    }

    public function testPutNumber() {
        $key = "paddy";
        $value = 123456.789;
        $idb_put = new iDBPut($this->_path , $key);
        $idb_put->storeInFile(true);
        $idb_put->storeInXattr(false);

        $this->assertTrue($idb_put->save($value));

        $new_dir = $this->getDirByKey($this->_path , $key);
        $this->assertTrue(is_dir($new_dir));
        $idb_type = iDBFileUtils::getFileContent($new_dir , iDBConfig::IDB_KEY_TYPE_NAME);
        $this->assertEquals($idb_type , iDBConfig::IDB_TYPE_INTEGER);

        $idb_value = iDBFileUtils::getFileContent($new_dir , iDBConfig::IDB_VALUE_NAME);
        $this->assertEquals($idb_value , $value);
    }

    public function testPutArray() {
        $key = "paddy";
        $value = array("id"=>112222,"avatar"=>"http://xxx.com/xxx.jpg","developer"=>true);
        $idb_put = new iDBPut($this->_path , $key);
        $idb_put->storeInFile(true);
        $idb_put->storeInXattr(false);
        $this->assertTrue($idb_put->save($value));

        $new_dir = $this->getDirByKey($this->_path , $key);
        $this->assertTrue(is_dir($new_dir));

        $idb_type = iDBFileUtils::getFileContent($new_dir , iDBConfig::IDB_KEY_TYPE_NAME);
        $this->assertEquals($idb_type , iDBConfig::IDB_TYPE_ARRAY);
        $idb_keys = iDBFileUtils::getFileContent($new_dir , iDBConfig::IDB_FIELDS_NAME);
        $this->assertEquals($idb_keys , join(iDBConfig::KEYS_SEPARATION,array_keys($value)));

        $dir_id = $this->getDirByKey($new_dir , "id");
        $this->assertTrue(is_dir($dir_id));
        $idb_type = iDBFileUtils::getFileContent($dir_id , iDBConfig::IDB_KEY_TYPE_NAME);
        $this->assertEquals($idb_type , iDBConfig::IDB_TYPE_INTEGER);

        $idb_value = iDBFileUtils::getFileContent($dir_id , iDBConfig::IDB_VALUE_NAME);

        $this->assertEquals($idb_value , $value["id"]);

        $dir_avatar = $this->getDirByKey($new_dir , "avatar");
        $this->assertTrue(is_dir($dir_avatar));
        $idb_type = iDBFileUtils::getFileContent($dir_avatar , iDBConfig::IDB_KEY_TYPE_NAME);
        $this->assertEquals($idb_type , iDBConfig::IDB_TYPE_STRING);

        $idb_value = iDBFileUtils::getFileContent($dir_avatar , iDBConfig::IDB_VALUE_NAME);
        $this->assertEquals($idb_value , $value["avatar"]);

        $dir_developer = $this->getDirByKey($new_dir , "developer");
        $this->assertTrue(is_dir($dir_developer));
        $idb_type = iDBFileUtils::getFileContent($dir_developer , iDBConfig::IDB_KEY_TYPE_NAME);
        $this->assertEquals($idb_type , iDBConfig::IDB_TYPE_BOOL);

        $idb_value = iDBFileUtils::getFileContent($dir_developer , iDBConfig::IDB_VALUE_NAME);
        $idb_value = $idb_value ? true : false;
        $this->assertTrue($idb_value);

    }

    public function testGet() {
        $key = "paddy";
        $value = array("id"=>112222,"avatar"=>"http://xxx.com/xxx.jpg");
        $idb_put = new iDBPut($this->_path , $key);
        $idb_put->storeInFile(true);
        $idb_put->storeInXattr(false);
        $this->assertTrue($idb_put->save($value));

        $idb_get = new iDBGet($this->_path , $key);
        $this->assertEquals($idb_get->value() , $value);

        $new_dir = $this->getDirByKey($this->_path , $key);
        $this->assertTrue(is_dir($new_dir));
    }

    public function testDelete() {

        $key = "paddy";
        $value = array("id"=>112222,"avatar"=>"http://xxx.com/xxx.jpg");
        $idb_put = new iDBPut($this->_path , $key);
        $idb_put->storeInFile(true);
        $idb_put->storeInXattr(false);
        $this->assertTrue($idb_put->save($value));

        $idb_get = new iDBGet($this->_path , $key);
        $this->assertEquals($idb_get->value() , $value);

        $new_dir = $this->getDirByKey($this->_path , $key);
        $this->assertTrue(is_dir($new_dir));

        $idb_delete = new iDBDelete($this->_path , $key);
        $this->assertTrue($idb_delete->save());

        $this->assertFalse(is_dir($new_dir));

    }

    private function getDirByKey($dir ,$key) {
        return $dir . DIRECTORY_SEPARATOR . $key;
    }

}