<?php
/**
 * DB UPDATE CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBupdate is simple object-relational update queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * Some examples:
 *
 * $result = DBupdate::factory()
 * ->table("dbtable")
 * ->set(array("name"=>"value"))
 * ->execute();
 *
 * $result = DBupdate::factory()
 * ->table("dbtable")
 * ->setAll(array("name"=>"value","text"=>"Hello world!"))
 * ->executeODKU();
 *
 * $result = DBupdate::factory()
 * ->table("dbtable")
 * ->set("id","1")
 * ->set("text","Hello world!")
 * ->executeODKU();
 *
 * $result = DBupdate::factory()
 * ->table("dbtable")
 * ->setPOST()
 * ->executeODKU();
 *
 */

require_once('DBquery.class.php');

class DBupdate extends DBquery
{

    function __construct()
    {
        parent::__construct();
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
     * Set array of values
     *
     * @param string $values - array of values? like - array("name"=>"value")
     * @return this object
     */
    public function setAll($values)
    {
        $this->names = array_keys($values);
        $this->values = array_values($values);
        return $this;
    }

	/**
     * Set array of values
     *
     * @param string $name - column name
     * @param string $value - value
     * @return this object
     */
    public function set($name, $value)
    {
        $this->$name = $value;       
        return $this;
    }

	/**
     * Set value
     *
     * @param string $table - table name
     * @return this object
     */
    public function __set($name, $value)
    {
        $this->names[] = $name;
        $this->values[] = $value;
        return $this;
    }

	/**
     * Set all values from POST
     *
     * @return this object
     */
    public function setPOST()
    {
        $this->initNames();
        $diff = array_diff($_POST, $this->values);        
        $this->setAll($diff);
        return $this;
    }

	/**
     * Render sql query
     */
    public function render()
    {
        $query = $this->db->parse(" UPDATE ?n SET ", $this->table);
        $q = array();
        foreach ($this->names as $k => $n) {
            if (!isset($this->values[$k])) {
                die(" Error. values array doesn`t match names array .");
            }
            $q[]= $this->db->parse(" ?n=?s ", $n, $this->values[$k]);
        }
        $query.= implode(",", $q);
        $query.= $this->getWhereQuery();
        return $query;
    }

	/**
     * Render sql query with ON DUPLICATE KEY UPDATE option
     */
    public function renderODKU()
    {
        $query = $this->db->parse(" INSERT INTO ?n ", $this->table);
        $query.= $this->db->parse(" ( ?p ) VALUES ( ?a ) ", implode(",", $this->names), $this->values);
        $query.= " ON DUPLICATE KEY UPDATE ";
        $q = array();
        foreach ($this->names as $k => $n) {
            if (!isset($this->values[$k])) {
                die(" Error. values array doesn`t match names array .");
            }
            $q[]= $this->db->parse(" ?n=?s ", $n, $this->values[$k]);
        }
        $query.= implode(",", $q);
        $query.= $this->getWhereQuery();
        return $query;
    }

	/**
     * Execute query with ON DUPLICATE KEY UPDATE option
     */
    public function executeODKU()
    {
        $query = $this->renderODKU();
        return $this->db->query($query);
    }

    public static function factory()
    {
        return new DBupdate();
    }

}