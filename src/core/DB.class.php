<?php
/**
 * simDB CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * simDB is simple object-relational update queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * Some examples:
 *
 * CREATE TABLE
 * $result = DB::create()
 * ->table("data_table")
 * ->id('id')
 * ->varchar('title', '255')
 * ->execute();
 *
 * INSERT INTO TABLE
 * $result = DB::insert()
 * ->into("data_table")
 * ->set('title','data1')
 * ->execute();
 *
 * UPDATE TABLE
 * $result = DB::update()
 * ->table("data_table")
 * ->set('id','1')
 * ->set('title','data2')
 * ->executeODKU();
 *
 * SELECT FROM TABLE
 * $result = DB::select()
 * ->names('id','title')
 * ->from("data_table")
 * ->where('id','1')
 * ->limit(3)
 * ->executeAll();
 *
 */

require_once('DBselect.class.php');
require_once('DBinsert.class.php');
require_once('DBupdate.class.php');
require_once('DBcreate.class.php');
require_once('DBdelete.class.php');

class DB
{
    protected static $table;

    /**
     * Select, insert , update or create
     *
     * @return this object
     */
    public static function __callStatic($name, array $params)
    {
        $funcs = array('select', 'insert' , 'update', 'create', 'delete', 'query');
        foreach ($funcs as $f) {
            if ($name===$f) {
                $classname = 'DB'.$f;
                if (class_exists($classname)) {
                    return new $classname(static::$table);
                } else {
                    die("Error! $classname class doesn`t found");
                }
            }
        }
    }

    public static function conn () {
        return DBSelect::factory()->connect();
    }
}
