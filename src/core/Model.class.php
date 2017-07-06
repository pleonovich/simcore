<?php 
/**
 * MODEL CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with popular MySQL queries
 *
 */

class Model extends DB {	
	
	protected static $table;
	protected $description;
	protected $names = array();
	
	function __construct($table) {
		self::$table = $table;
	}

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
			->from(self::$table)
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
	public function __get ($name) {		
		$res = false;
		try {
			$res = self::select()
			->names($name)
			->from(self::$table)
			->executeRow();
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
			->from(self::$table)
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
			->from(self::$table)
			->where($name,"=",$value)
			->executeRow();
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
			->table(self::$table)
			->setPOST()
			->executeODKU();			
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	protected function schema ($create){
		$create = false;
	}

	public function migrate () {
		$create = DB::create()->table(self::$table);
		$this->schema($create);
		if(!$create) return false;
		if(is_a($create,'DBcreate')) return $create->execute();
		return false;
	}

	protected function insert ($insert) {
		$insert = false;
	}

	public function insertData () {
		$insert = DB::insert()->into(self::$table);
		$this->insert($insert);
		if(!$insert) return false;
		return $insert->execute();
	} 
	
	
}

?>	