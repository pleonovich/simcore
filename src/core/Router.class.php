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
require_once("Response.class.php");

class Router
{
    
    private $url_path;
    private $routes = array();
    public $module = 'NotFoundController';
    public $action = 'index';
    public $params = array();
    public $function = NULL;
    public $body = array();

    private $request;
    private $response;
    
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
    public function set($method, $pattern, $classmethod, array $aliases = null)
    {
        $next = count($this->routes);
        $this->routes[$next]['method'] = $method;
        if (is_string($classmethod)) {
            $cm = explode('@',$classmethod);
            $this->routes[$next]['className'] = $cm[0];
            $this->routes[$next]['classMethod'] = $cm[1];
        } else if (is_callable($classmethod)){
            $this->routes[$next]['className'] = '';
            $this->routes[$next]['classMethod'] = '';
            $this->routes[$next]['function'] = $classmethod;
        }
        $this->routes[$next]['pattern'] = $pattern;
        if ($aliases!=null) {
            $this->routes[$next]['aliases'] = $aliases;
        }
        return $this;
    }

    function get($regexp, $classmethod, $aliases = null) {
        $this->set("GET", $regexp, $classmethod, $aliases);
        return $this;
    }
    
    function post($regexp, $classmethod, $aliases = null) {
        $this->set('POST', $regexp, $classmethod, $aliases);
        return $this;
    }
    
    function put($regexp, $classmethod, $aliases = null) {
        $this->set("PUT", $regexp, $classmethod, $aliases);
        return $this;
    }
    
    function delete($regexp, $classmethod, $aliases = null) {
        $this->set("DELETE", $regexp, $classmethod, $aliases);
        return $this;
    }

	/**
     * Initialisation
     *
     * @return this object
     */
    private function init()
    {
        foreach ($this->routes as $map) {
            if (preg_match($map['pattern'], $this->url_path, $matches)
                && $_SERVER['REQUEST_METHOD'] === $map['method']) {
            
                array_shift($matches);
                if(isset($map['function'])) {
                    $this->function = $map['function'];
                }
                foreach ($matches as $index => $value) {
                    $this->params[$map['aliases'][$index]] = $value;
                }
                $this->module = $map['className']."Controller";
                $this->action = $map['classMethod'];                
                $this->body = file_get_contents('php://input');
                $this->body = json_decode($this->body, TRUE);
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
        foreach ($this->routes as $map) {
            if (preg_match($map['pattern'], $this->url_path, $matches) && $_SERVER['REQUEST_METHOD'] === $map['method']) {
                array_shift($matches);
                foreach ($matches as $index => $value) {
                    $this->params[$map['aliases'][$index]] = $value;
                }
                $this->module = $map['className']."Controller";
                $this->action = $map['classMethod'];  
                if (count($this->params)>0) {
                    $_GET = $this->params;
                }
                $this->body = file_get_contents('php://input');
                $this->body = json_decode($this->body, TRUE);
                $this->request = new Request($this->params, $this->body);
                $this->response = new Response();                
                if(isset($map['function'])) {
                    $this->function = $map['function'];
                }
                if(is_callable($this->function)) {
                    $func = $this->function;
                    return $func($this->request, $this->response);
                }
                if (class_exists($this->module)) {
                    $MODULE = new $this->module();
                    $action = $this->action;
                    if (method_exists($MODULE, $this->action)) {
                        $MODULE->$action($this->request, $this->response);
                    } else {
                        die("Error! Method ".$this->action." is not found");
                    }
                } else {
                    die("Error! Class ".$this->module." is not found");
                }
                break;
            }
        }
    }
    
    public static function factory()
    {
        return new Router();
    }

}