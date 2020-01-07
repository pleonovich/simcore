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
    public $function = NULL;
    public $params = array();
    public $body = array();
    
    private $request;
    private $response;

    function __construct()
    {
        $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY );
        parse_str($params, $this->url_path);
    }

	/**
     * Set default route
     *
     * @param string $classmethod - controller name and method names
     * @return this object
     */
    public function setDefault($classmethod)
    {
        $cm = explode('@',$classmethod);
        $this->module = $cm[0]."Controller";
        $this->action = $cm[1];
        return $this;
    }

	/**
     * Set route
     *
     * @param string $mod - value of parametr in url by index - $this->index
     * @param string $classmethod - controller name and method names
     * @return this object
     */
    public function set($method, $mod, $classmethod)
    {
        $this->routes[$mod]['method'] = $method;
        if (is_string($classmethod)) {
            $cm = explode('@',$classmethod);
            if (count($cm) < 2) {
                throw new \Exception("Invalid classmethod value - '$classmethod'");
            }
            $this->routes[$mod]['className'] = $cm[0];
            $this->routes[$mod]['classMethod'] = $cm[1];
        } else if (is_callable($classmethod)){
            $this->routes[$mod]['className'] = '';
            $this->routes[$mod]['classMethod'] = '';
            $this->routes[$mod]['function'] = $classmethod;
        }
        return $this;
    }

    function get($mod, $classmethod) {
        $this->set("GET", $mod, $classmethod);
        return $this;
    }
    
    function post($mod, $classmethod) {
        $this->set('POST', $mod, $classmethod);
        return $this;
    }
    
    function put($mod, $classmethod) {
        $this->set("PUT", $mod, $classmethod);
        return $this;
    }
    
    function delete($mod, $classmethod) {
        $this->set("DELETE", $mod, $classmethod);
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
                if(isset($this->routes[$mod]['function'])) {
                    $this->function = $this->routes[$mod]['function'];
                }
                $this->module = $this->routes[$mod]['className']."Controller";
                $this->action = $this->routes[$mod]['classMethod'];
                $this->body = file_get_contents('php://input');
                $this->body = json_decode($this->body, TRUE);
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
        $this->body = file_get_contents('php://input');
        $this->body = json_decode($this->body, TRUE);
        $this->request = new Request($_GET, $this->body);
        $this->response = new Response();
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
        return $this;
    }

    public static function factory()
    {
        return new SimpleRouter();
    }

}