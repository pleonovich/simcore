<?php
/**
 * AUTOLOADER CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Classes autoloader
 *
 */

$bootstrap = new Bootstrap();
spl_autoload_register(array($bootstrap, 'autoload'));

class Bootstrap
{

    private $links = array(
        'src/config/%s.class.php',
        'src/core/%s.class.php',
        'src/objects/%s.class.php',
        'src/lib/%s.class.php',
        'src/models/%s.class.php',
       'src/modules/%s.class.php',
        'src/controllers/%s.class.php',
        );
    
    function __construct()
    {
        $this->clearLog();
    }

    /**
     * Load classes
     */
    public function autoload($className)
    {
        $result = false;
        foreach ($this->links as $link) {
            $file_path = $_SERVER['DOCUMENT_ROOT']."/".sprintf( $link, $className );
            $this->log("file_path: ".$file_path);
            if (file_exists($file_path)) {
                include_once($file_path);
                $result = true;
                $this->log($className." is loaded!");
            }
        }
        if (!$result) {
            $this->log($className." loading failed!");
        }
    }
    
	/**
     * Log information about loaded classes
     */
    private function log($text)
    {
        $fd = fopen($_SERVER['DOCUMENT_ROOT']."/src/log/autoloader_log.txt", 'a+') or die("Autoloader: failed to write log!");
        $str = date('[Y.m.d] [H:i:s]')." class - ".$text."\n";
        fwrite($fd, $str);
        fclose($fd);
    }

	/**
     * Clear log information about loaded classes
     */
    private function clearLog()
    {
        $fd = fopen($_SERVER['DOCUMENT_ROOT']."/src/log/autoloader_log.txt", 'a+') or die("Autoloader: failed to write log!");
        fwrite($fd, "");
        fclose($fd);
    }

}