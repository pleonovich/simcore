<?php
namespace SimCore\core;

/**
 * Bootstrap class
 * ===============
 *
 * @author leonovich.pavel@gmail.com
 * @version 1.0.0
 * Classes autoloader
 *
 */

$bootstrap = new Bootstrap();
spl_autoload_register(array($bootstrap, 'autoload'));

class Bootstrap
{
    private static $root = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..";
    private static $links = array(
        'src/config/%s.php',
        'src/core/%s.php',
        'src/objects/%s.php',
        'src/lib/%s.php',
        '/models/%s.php',
        '/modules/%s.php',
        '/controllers/%s.php'
        );
    
    function __construct()
    {
        // $this->clearLog();
    }

    public static function config($root, $links) {
        self::$root = $root;
        self::$links = $links;
    }

    /**
     * Load classes
     */
    public function autoload($className)
    {
        $result = false;
        foreach (self::$links as $link) {
            $file_path = self::$root . DIRECTORY_SEPARATOR . sprintf( $link, $className );
            // $this->log("file_path: ".$file_path);
            if (file_exists($file_path)) {
                include_once($file_path);
                $result = true;
                // $this->log($className." is loaded!");
            }
        }
        if (!$result) {
            throw new \Exception($className." loading failed!");
            // $this->log($className." loading failed!");
        }
    }
    
	/**
     * Log information about loaded classes
     */
    private function log($text)
    {
        $fd = fopen(self::$root."log".DIRECTORY_SEPARATOR."autoloader_log.txt", 'a+') or die("Autoloader: failed to write log!");
        $str = date('[Y.m.d] [H:i:s]')." class - ".$text."\n";
        fwrite($fd, $str);
        fclose($fd);
    }

	/**
     * Clear log information about loaded classes
     */
    private function clearLog()
    {
        $fd = fopen(self::$root."log".DIRECTORY_SEPARATOR."autoloader_log.txt", 'w+') or die("Autoloader: failed to write log!");
        fwrite($fd, "");
        fclose($fd);
    }

}