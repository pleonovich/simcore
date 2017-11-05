<?php
/**
 * SEF URL CLASS
 * =============
 * Simple way to work with search engine friendly urls
 * 
 * @author leonovich.pavel@gmail.com
 * @version 1.0.0
 * 
 */

class SefURL
{
    
    protected $GET = null;
    protected $URL_DATA = array();
    protected $TYPE = 1;
    
    protected $DOMEN_NAME;
    protected $HTTP_HOST;
    
    function __construct()
    {
        $this->init_url();
    }
    
	/**
     * URL initialisation
     */
    function init_url()
    {
        $this->GET = $_GET;
        foreach ($this->GET as $key => $value) {
            $this->URL_DATA[$key]=$value;
        }
        $this->DOMEN_NAME = Config::DOMEN_NAME;
        $this->HTTP_HOST = Config::HTTP_HOST;
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
    private function render($url, $except = null, $new = false)
    {
        $gen_url = array();
        if (!is_array($except)) {
            $except = array($except);
        }
        foreach ($url as $key => $value) {
            if (!$new) {
                if (!in_array($key, $except)) {
                    if ($value!="") {
                        if(!is_numeric($key)) $gen_url[] = $key;
                        $gen_url[] = $value;
                    }
                }
            } elseif ($new) {
               	if(!is_numeric($key)) $gen_url[] = $key;
            	$gen_url[] = $value;
            }
        }
        return $this->HTTP_HOST."/".implode("/", $gen_url);
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
     * Get url
     *
     * @param string|array $replace - params to replace
     * @param string|array $except - params to ignore
     * @return string - rendered url
     */
    public function get($replace = null, $except = null)
    {
        $url=$this->URL_DATA;
        if ($replace!=null) {
            $url = $this->replace($url, $replace);
        }
        return $this->render($url, $except);
    }

}