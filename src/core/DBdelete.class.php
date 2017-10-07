<?php
/**
 * DB DELETE CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBdelete is simple object-relational delete queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * Some examples:
 *
 * $result = DBdelete::factory()
 * ->from("dbtable")
 * ->where(array("name"=>"value"))
 * ->execute();
 * 
 *
 */

 require_once('DBquery.class.php');
 
 class DBdelete extends DBquery
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
     public function from($table)
     {
         $this->table = $table;
         return $this;
     }
 
     /**
      * Render sql query
      */
     public function render()
     {
         $query = $this->db->parse(" DELETE FROM ?n ", $this->table);
         $query.= $this->getWhereQuery();
         return $query;
     }
 
     public static function factory()
     {
         return new DBdelete();
     }
 
 }