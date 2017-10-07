<?php 
/**
 * MODEL CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with popular MySQL queries
 *
 */

class Model extends DB {
	
	protected $description;
	protected $names = array();
	protected $conn;
	
	// function __construct($table) {
	// 	static::$table = $table;
	// 	$this->conn = self::conn();
	// }

	/**
     * Get all from db table
     *
     * @return boolean - result
     */
	public static function all () {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from(static::$table)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get names from db table
     *
     * @return boolean - result
     */
	public static function getNames () {
		$res = false;
		try {
			$res = self::select()
			->names(func_get_args())
			->from(static::$table)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get all from db table
     *
     * @return boolean - result
     */
	public static function column ($name) {
		$res = false;
		try {
			$res = self::select()
			->names($name)
			->from(static::$table)
			->executeCol();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get row by id
     *
     * @param int $id - row id
     * @return array - result
     */
	public static function getById ( $id ) {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from(static::$table)
			->where("id","=",$id)
			->executeRow();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get row by value
     *
     * @param string $name - column name
     * @param string $value - value
     * @return array - result
     */
	public static function getByValue ( $name, $value ) {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from(static::$table)
			->where($name,"=",$value)
			->executeRow();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
	 * Get names from db table
	 *
	 * @param string $names - select columns
     * @param string $name - column name
     * @param string $value - value
     * @return boolean - result
     */
	 public static function namesByValue () {
		$args = func_get_args();
		$names = array_shift($args);
		$name = array_shift($args);
		$value = array_shift($args);
		$res = false;
		try {
			$res = self::select()
			->names($names)
			->from(static::$table)
			->where($name,"=",$value)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}
	
	/**
     * Save current post data to db
     *
     * @return boolean - result
     */
	public static function save () {
		$res = false;
		try {
			$res = self::update()
			->table(static::$table)
			->setPOST()
			->executeODKU();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}
	
	/**
     * Delete data by value
     *
     * @return boolean - result
     */
	public static function remove ( $name, $value ) {
		$res = false;
		try {
			$res = self::delete()
			->from(static::$table)
			->where($name, $value)
			->execute();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	protected static function schema ($create){
		$create = false;
	}

	public static function migrate () {
		$create = self::create()->table(static::$table);
		static::schema($create);
		if(!$create) return false;
		if(is_a($create,'DBcreate')) return $create->execute();
		return false;
	}

	protected static function setData ($insert) {
		$insert = false;
	}

	public static function insertData () {
		$insert = self::insert()->into(static::$table);
		static::setData($insert);
		if(!$insert) return false;
		if(is_a($insert,'DBinsert')) return $insert->execute();
		return false;
	}
	
	public static function factory ( $table ) {
		return new Model($table);
	}
	
}