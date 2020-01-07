<?php
namespace SimCore\core;

use SimCore\core\Config as Config;

/**
 * URL CLASS
 * ==========
 * 
 * @author leonovich.pavel@gmail.com
 * @version 1.0.0
 * Simple way to work with urls
 *
 * $url = new URL();
 * $url->getNew();
 * $url->get();
 *
 */

class URL
{
    
    protected $GET = null;
    protected $URL_DATA = array();
    protected $TYPE = 1;
    
    protected $DOMEN_NAME;
    protected $HTTP_HOST;
    protected $FILE = "index.php";
    
    function __construct()
    {
        $this->init_url();
    }

    /**
     * set file
     */
    public function setFile($file)
    {
        $this->FILE = $file;
    }

    /**
     * URL initialisation
     */
    private function init_url()
    {
        $this->GET = $_GET;
        foreach ($this->GET as $key => $value) {
            $this->URL_DATA[$key] = $value;
        }
        $this->DOMEN_NAME = Config::$domen_name;
        $this->HTTP_HOST = Config::$http_host;
    }
    
	/**
     * Replace params from url
     *
     * @param array $url - url data
     * @param array $replace - params to replace
     * @return array - updated url data
     */
    private function replace($url, $replace)
    {
        foreach ($replace as $key => $value) {
            if (isset($url[$key])) {
                $url[$key] = $value;
            } else {
                $url[$key] = $value;
            }
        }
        return $url;
    }

    /**
     * Render updated url
     *
     * @param array $url - url data
     * @param string|array $except - params to ignore
     * @param boolean $new - ignore params from current GET request
     * @return string - updated url
     */
    private function render($url, $except = array(), $new = false, $hostname = true)
    {
        $gen_url = ($hostname) ? $this->HTTP_HOST."/".$this->FILE : $this->FILE ;
        $params = array();
        if (!is_array($except)) {
            $except = array($except);
        }
        foreach ($url as $key => $value) {
            if (!$new) {
                if (!in_array($key, $except)) {
                    if ($value!="") {
                        $params[] = $key."=".$value;
                    }
                }
            } elseif ($new) {
                $params[] = $key."=".$value;
            }
        }
        if (count($params) > 0) {
            return $gen_url."?".implode("&", $params);
        } else {
            return $gen_url;
        }
    }

	/**
     * Get new url
     *
     * @param array $url - url data
     * @return string - rendered url
     */
    public function getNew($url = null)
    {
        return $this->render($url, null, true);
    }

	/**
     * Get new url
     *
     * @param array $url - url data
     * @return string - rendered url
     */
    public function getParams($replace = null, $except = null)
    {   
        $url = $this->URL_DATA;
        if ($replace!=null) {
            $url = $this->replace($url, $replace);
        }
        return $this->render($url, $except, false, false);
    }

    /**
     * Get url
     *
     * @param string|array $replace - params to replace
     * @param string|array $except - params to ignore
     * @return string - rendered url
     */
    public function get($replace = null, $except = null)
    {
        $url = $this->URL_DATA;
        if ($replace!=null) {
            $url = $this->replace($url, $replace);
        }
        return $this->render($url, $except);
    }

}
