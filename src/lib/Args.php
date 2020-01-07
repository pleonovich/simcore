<?php
namespace SimCore\lib;
/**
 * ARGS CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with data sent by form
 *
 * Key features:
 *
 * - easy way to work with global arrays when you need to set default values to its elements
 * - easy work with input forms? to set default or entered values
 *
 * Some examples:
 *
 * $id = Args::getOne($_GET, 'id', null);
 *
 * $args = Args::getArray($_GET, array('id'=>null));
 * $id = $args['id'];
 *
 * $args = Args::factory($_GET)->set('id',null);
 * $id = $args->id;
 *
 */

class Args
{

    private $DATA = array();
    private $ARGS = array();
    
    public function __construct($DATA)
    {
        $this->DATA = $DATA;
    }
    
    /**
     * Getting default value if index doesn`t exists
     *
     * @param array $DATA - global array
     * @param string $name - index in global array
     * @param string $default - default value for index in global array
     * @return string - calculated value for index in global array
     */
    public static function getOne($DATA, $name, $default = null)
    {
        if (isset($DATA[$name])) {
            return $DATA[$name];
        } else {
            return $default;
        }
    }
    
    /**
     * Getting array of default values if indexes doesn`t exists
     *
     * @param array $DATA - global array
     * @param array $kit - array of default values for indexes
     * @return array - calculated array of values for index in global array
     */
    public static function getArray($DATA, $kit)
    {
        $args = array();
        if (is_array($kit) && count($kit)>0) {
            foreach ($kit as $key => $value) {
                if (isset($DATA[$key])) {
                    $args[$key] = $DATA[$key];
                }
            }
        }
        return $args;
    }
    
	/**
     * Set default value if indexes doesn`t exists
     *
     * @param array $name - parameter name
     * @param array $deffault - default value
     * @return this object
     */
    public function set($name, $default = null)
    {   
        if(is_array($name)) {            
            foreach($name as $k=>$v) {                
                $this->ARGS[$k] = self::getOne($this->DATA, $k, $v);                
            }
        } else {
            $this->ARGS[$name] = $default;
        }        
        return $this;
    }
    
	/**
     * Get arguments
     *
     * @return array - arguments
     */
    public function getArgs()
    {
        return $this->getArray($this->DATA, $this->ARGS);
    }

    /**
     * Set default value if indexes doesn`t exists
     *
     * @param array $name - parameter name
     * @param array $deffault - default value
     * @return this object
     */
    public function __set($name, $default = null)
    {
        $this->set($name, $default);
    }

    /**
     * Get value
     *
     * @param array $name - parameter name
     * @param array $deffault - default value
     * @return this object
     */
    public function __get($name)
    {
        if (isset($this->ARGS[$name])) {
            return $this->ARGS[$name];
        } else {
            return null;
        }
    }

    /**
     * Unset last element of array
     *
     * @param array $array - input array
     * @return array output array
     */
    public function unsetLast($array)
    {
		unset($array[count($array)-1]);
        return $array;
    }
    
    public static function factory($DATA)
    {
        return new Args($DATA);
    }

}