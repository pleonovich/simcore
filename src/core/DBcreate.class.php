<?php
/**
 * DB CREATE CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBcreate is simple object-relational create queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * 
 * Example:
 *
 * $result = DBcreate::factory()
 * ->table("test_create")
 * ->id('id')
 * ->varchar('name', '50')
 * ->date('birthdate')
 * ->text('about')
 * ->execute();
 *
 */

require_once('DBquery.class.php');

class DBcreate extends DBquery
{

    private $charset = 'utf8';
    private $collate = 'utf8_general_ci';

    function __construct( $table )
    {
        parent::__construct($table);
    }

    /**
     * Set table name
     *
     * @param string $table - table name
     * @return this object
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set field description
     *
     * @param string $name - column name
     * @param string $type - type
     * @param string $length - length
     * @param string $Null - Null
     * @param string $key - key
     * @param string $default - default
     * @return this object
     */
    private function set($name, $type, $length = null, $Null = false, $key = false, $default = null)
    {
        $d = array(
            'Field'=>$name,
            'Type'=>$type,
            'Length'=>$length,
            'Null'=>$Null,
            'Key'=>$key,
            'Default'=>$default
        );
        $this->description[] = $d;
        return $this;
    }

    /**
     * Set id field
     *
     * @param string $name - column name
     * @return this object
     */
    public function id($name='id')
    {
        return $this->set($name, 'INT', 11, false, true);
    }

    /**
     * Set int field
     *
     * @param string $name - column name
     * @param string $length - length
     * @param string $Null - Null
     * @param string $key - key
     * @param string $default - default
     * @return this object
     */
    public function int($name, $length = 11, $Null = false, $key = false, $default = null)
    {
        return $this->set($name, 'INT', $length, $Null, $key, $default);
    }

    /**
     * Set varchar field
     *
     * @param string $name - column name
     * @param string $length - length
     * @param string $Null - Null
     * @param string $key - key
     * @param string $default - default
     * @return this object
     */
    public function varchar($name, $length = 255, $Null = false, $key = false, $default = null)
    {
        return $this->set($name, 'VARCHAR', $length, $Null, $key, $default);
    }

    /**
     * Set text field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function text($name, $Null = false, $default = null)
    {
        return $this->set($name, 'TEXT', null, $Null, false, $default);
    }

    /**
     * Set float field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function float($name, $length = 255, $Null = false, $key = false, $default = null)
    {
        return $this->set($name, 'FLOAT', $length, $Null, $key, $default);
    }

    /**
     * Set double field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function double($name, $length = 255, $Null = false, $key = false, $default = null)
    {
        return $this->set($name, 'DOUBLE', $length, $Null, $key, $default);
    }

    /**
     * Set date field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function date($name, $Null = false, $default = null)
    {
        return $this->set($name, 'DATE', null, $Null, false, $default);
    }

    /**
     * Set time field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function time($name, $Null = false, $default = null)
    {
        return $this->set($name, 'TIME', null, $Null, false, $default);
    }

    /**
     * Set datetime field
     *
     * @param string $name - column name
     * @param string $Null - Null
     * @param string $default - default
     * @return this object
     */
    public function datetime($name, $Null = false, $default = null)
    {
        return $this->set($name, 'DATETIME', null, $Null, false, $default);
    }

    /**
     * Render sql query
     */
    public function render()
    {
        $query = $this->db->parse(" CREATE TABLE IF NOT EXISTS ?n ", $this->table);
        $q = array();
        foreach ($this->description as $f) {
            $s = " ".$f['Field']." ".$f['Type'];
            $s.= !in_array($f['Type'], array('TEXT','DATE','TIME','DATETIME')) ? "(".$f['Length'].")" : "";            
            $s.= $f['Null'] ? " NULL" : " NOT NULL";
            $s.= $f['Default']!==null ? " DEFAULT ".$f['Default'] : "";
            $s.= $f['Key'] ? " AUTO_INCREMENT PRIMARY KEY " : null;
            $q[] = $s;
        }
        $query.= " (\n".implode(",\n", $q)." \n) CHARACTER SET ".$this->charset." COLLATE ".$this->collate."; ";
        $query.= $this->getWhereQuery(); 
        return $query;
    }

    public static function factory()
    {
        return new DBcreate();
    }

}