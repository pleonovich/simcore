<?php
/**
 * ROUTER CLASS 1.0.1
 *
 * @author leonovich.pavel@gmail.com
 * Router for working with search engine friendly url like http://site.com/id/1
 *
 * Example:
 *
 * Router::factory()
 * ->set('~^/$~', 'Index', 'index')
 * ->set('~^/id/([0-9]+)$~', 'Index', 'index', array('id'))
 * ->run();
 *
 */

class Router
{
    
    private $url_path;
    private $routes = array();
    public $module = 'Not_Found';
    public $action = 'main';
    public $params = array();
    
    function __construct()
    {
        $this->url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Set route
     *
     * @param string $pattern - url regular expression
     * @param string $class - controller name
     * @param string $method - controller method name
     * @param array $aliases - url params
     * @return this object
     */
    public function set($pattern, $class, $method, array $aliases = null)
    {
        $next = count($this->routes);
        $this->routes[$next]['pattern'] = $pattern;
        $this->routes[$next]['class'] = $class;
        $this->routes[$next]['method'] = $method;
        if ($aliases!=null) {
            $this->routes[$next]['aliases'] = $aliases;
        }
        return $this;
    }

	/**
     * Initialisation
     *
     * @return this object
     */
    public function init()
    {
        foreach ($this->routes as $map) {
            if (preg_match($map['pattern'], $this->url_path, $matches)) {
            //var_dump($matches);
                array_shift($matches);
                foreach ($matches as $index => $value) {
                    $this->params[$map['aliases'][$index]] = $value;
                }
                $this->module = $map['class']."Controller";
                $this->action = $map['method'];
                break;
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
        if (count($this->params)>0) {
            $_GET = $this->params;
        }
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
        return new Router();
    }

}