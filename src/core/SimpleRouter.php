<?php
namespace SimCore\core;

/**
 * SIMPLE ROUTER CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Router for working with standart url like http://site.com/index.php?mod=main&id=1
 *
 * Example:
 *
 * SimpleRouter::factory()
 * ->set('main', 'Main', 'index')
 * ->set('login', 'Login', 'index')
 * ->run();
 *
 */

class SimpleRouter
{

    private $url_path;
    private $routes = array();
    private $index = 'mod';
    public $module = 'Not_Found';
    public $action = 'main';
    public $params = array();

    function __construct()
    {
        $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY );
        parse_str($params, $this->url_path);
    }

	/**
     * Set default route
     *
     * @param string $class - controller name
     * @param string $method - controller method name
     * @return this object
     */
    public function setDefault($class, $method)
    {
        $this->module = $class."Controller";
        $this->action = $method;
        return $this;
    }

	/**
     * Set route
     *
     * @param string $mod - value of parametr in url by index - $this->index
     * @param string $class - controller name
     * @param string $method - controller method name
     * @return this object
     */
    public function set($mod, $class, $method)
    {
        $this->routes[$mod]['class'] = $class;
        $this->routes[$mod]['method'] = $method;        
        return $this;
    }

	/**
     * Initialisation
     *
     * @return this object
     */
    public function init()
    {
        if (isset($this->url_path[$this->index])) {
            $mod = $this->url_path[$this->index];
            if (isset($this->routes[$mod])) {
                $this->module = $this->routes[$mod]['class']."Controller";
                $this->action = $this->routes[$mod]['method'];
            } else {
                die('Error! Route '.$this->index.' doesn`t found.');
            }
        }
        return $this;
    }

	/**
     * Run controller
     */
    public function run()
    {
        $this->init();
        if (class_exists($this->module)) {
            $MODULE = new $this->module();
            $action = $this->action;
            if (method_exists($MODULE, $this->action)) {
                $MODULE->$action();
            } else {
                die("Error! Method ".$this->action." is not found");
            }
        } else {
            die("Error! Class ".$this->module." is not found");
        }
        return $this;
    }

    public static function factory()
    {
        return new SimpleRouter();
    }

}