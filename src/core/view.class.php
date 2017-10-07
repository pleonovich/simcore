<?php
/**
 * VIEW CLASS 1.0.0
 *
 * Example:
 *
 * View::factory()
 * ->bind("content",$this->content)
 * ->render('layout');
 *
 */

class View
{
    protected static $_global_params = array();
    private $dir_tmpl = "src/views";
    private $params = array();

    /**
     * Construct
     *
     * @param string $dir - views directory
     */
    public function __construct($dir = null)
    {
        if ($dir!=null) {
            $this->dir_tmpl = $dir;
        }
    }

    /**
     * Bind variable to view
     *
     * @param string $name - variable name
     * @param string|int|array|object $value - variable value
     * @return View - this object
     */
    public function bind($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Bind globalvariable to view
     *
     * @param string $name - variable name
     * @param string|int|array|object $value - variable value
     * @return View - this object
     */
    public static function bind_global($name, $value)
    {
        self::$_global_params[$name] = $value;        
    }
    
	/**
     * Bind array of variables to view
     *
     * @param array $params - array with variables, like - array('id'=>1)
     * @return View - this object
     */
    public function bindArray($params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }
    
	/**
     * Render view
     *
     * @param string $file - view file name
     * @param boolean $return - return or echo rendered view 
     * @return string - rendered view 
     */
    public function render($file, $return = false)
    {
        $template = $_SERVER['DOCUMENT_ROOT']."/".$this->dir_tmpl."/".$file.".view.php";
        if (!file_exists($template)) {
            die("Error! File '".$template."' doesn`t exsist");
        }
        extract($this->params);
        ob_start();
        include($template);
        if ($return) {
            return ob_get_clean();
        } else {
            echo ob_get_clean();
        }
    }
    
    final public static function factory($dir = null)
    {
        return new View($dir);
    }

}