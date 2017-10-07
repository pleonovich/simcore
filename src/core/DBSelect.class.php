<?php
/**
 * DB SELECT CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBselect is simple object-relational select queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * Some examples:
 *
 * $result = DBselect::factory()
 * ->names("id","name","phone","age")
 * ->from("dbtable")
 * ->executeAll();
 *
 * $result = DBselect::factory()
 * ->names("id","name","phone","age")
 * ->from("dbtable")
 * ->where("id","=",1)
 * ->executeRow();
 *
 */

require_once('DBquery.class.php');

class DBselect extends DBquery
{
    
    protected $distinct = false;

    function __construct($table=null)
    {
        parent::__construct($table);
    }
    
	/**
     * Set column names
     *
     * @param string $names - db cells names
     * @return this object
     */
    public function names()
    {
		$names = func_get_args();
        if (count($names)>0) {
            if(is_array($names[0])) { 
                $this->names = $names[0];
            } else {
                $this->names = $names;
            }
        } else {
			$this->names = array("*");
		}
        return $this;
    }

    /**
     * Set distinct is true
     *
     * @return this object
     */
    public function distinct()
    {
		$this->distinct = true;
        return $this;
    }

    /**
     * Set column names
     *
     * @param string $names - db cells names
     * @return this object
     */
    public function all()
    {
        $this->names = array("*");
        return $this;
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
        $query = " SELECT ".($this->distinct ? 'DISTINCT ' : '').implode(",", $this->names);
        $query.= $this->db->parse(" FROM ?n ", $this->table);
        $query.= $this->getJoinQuery();
        $query.= $this->getWhereQuery();
        $query.= $this->getGroupByQuery();
        $query.= $this->getOrderByQuery();
        $query.= $this->getLimitQuery(); //LOG::write($query,"query");
        return $query;
    }

	/**
     * Execute all rows
     */
    public function executeAll()
    {
        $query = $this->render();
        return $this->db->getAll($query);
    }

	/**
     * Execute one row
     */
    public function executeRow()
    {
        $query = $this->render();
        return $this->db->getRow($query);
    }

	/**
     * Execute one column
     */
    public function executeCol()
    {
        $query = $this->render();
        return $this->db->getCol($query);
    }
    	
    public static function factory()
    {
        return new DBSelect();
    }
}