<?php
/**
 * DB INSERT CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBinsert is simple object-relational update queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * Some examples:
 *
 * $result = DBinsert::factory()
 * ->into("dbtable")
 * ->set(array("name"=>"value"))
 * ->execute();
 *
 * $result = DBinsert::factory()
 * ->into("dbtable")
 * ->setAll(array("title"=>"Hello","text"=>"Hello world!"))
 * ->execute();
 *
 * $result = DBinsert::factory()
 * ->into("dbtable")
 * ->set("title","Hello")
 * ->set("text","Hello world!")
 * ->execute();
 *
 * $result = DBinsert::factory()
 * ->into("dbtable")
 * ->setPOST()
 * ->execute();
 *
 */

require_once('DBupdate.class.php');

class DBinsert extends DBupdate
{

    function __construct($table)
    {
        parent::__construct($table);
    }

	/**
     * Set table name
     *
     * @param string $table - table name
     * @return this object
     */
    public function into($table)
    {
        $this->table = $table;
        return $this;
    }

	/**
     * Render sql query
     */
    public function render()
    {
        $query = $this->db->parse(" INSERT INTO ?n ", $this->table);
        $query.= $this->db->parse(" ( ?p ) VALUES ( ?a ) ", implode(",", $this->names), $this->values);       
        $query.= $this->getWhereQuery();
        return $query;
    }

    public static function factory()
    {
        return new DBinsert();
    }

}