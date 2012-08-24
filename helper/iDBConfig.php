<?php
/**
 * Description of iDBConfig.php
 * 
 * Author moupeili <moupeili@hotmail.com>
 * Date: 2012-08-21
 * Time: 下午2:35
 */
 
class iDBConfig {

    const IDB_VER = "0.0.2";

    const IDB_SPEC_VER = "0.2";

    const IDB_KEY_TYPE_NAME = ".type";

    const IDB_VALUE_NAME = ".value";

    const IDB_FIELDS_NAME = ".keys";

    const IDB_KEY_PREFIX = "user.";

    const FORCE_UPDATE = true;   //true：可以随类型变更

    const IDB_TYPE_STRING = "string";

    const IDB_TYPE_BOOL = "bool";

    const IDB_TYPE_INTEGER = "integer";

    const IDB_TYPE_ARRAY = "array";

    const LAZY_LOAD = true;  //针对array，是否开始懒加载模式

    const KEYS_SEPARATION = "\n";

    const STORE_IN_FILE = true;

    const STORE_IN_XATTR = false;


}
