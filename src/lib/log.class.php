<?php
/**
 * LOG CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to write log information into txt file from PHP
 *
 * Key features:
 *
 * - easy way to write variable information into a file
 * - Supports diferent kinds of information, such as: string, integer, array, boolean, null
 *
 * Some examples:
 *
 * LOG::setPath('Here path to log folder');
 * LOG::write('Here is your log information','Log title');
 * LOG::writeError('Here is your error log information','Error log title');
 * LOG::writeException('Here is exception object','Exception information');
 * LOG::clear();
 * LOG::border('Border title');
 *
 */

class LOG
{
    
    public static $path = "log"; // log file saving path
    public static $show_caller = false; // log file saving path
    
    /**
     * Set saving path
     *
     * @param string $path - saving path
     */
    public static function setPath($path)
    {
        if (is_dir(".".DIRECTORY_SEPARATOR.$path)) {
            self::$path = $path;
        }
    }
     
    /**
     * Write log to file
     *
     * @param string|integer|array|boolean|null $text - log text
     * @param string $title - log title
     * @param string $filename - filename part after date
     */
    public static function write($text, $title = null, $filename = null)
    {
        //self::renderArray(debug_backtrace(),"debug_backtrace");
        if (is_array($text)) {
            $log = self::renderArray($text, $title);
        } elseif (is_string($text) or is_bool($text) or is_integer($text) or is_null($text)) {
            $log = self::renderString($text, $title);
        }
        self::writeLog($log, $title, $filename);
    }
    
    /**
     * Write log to error file
     *
     * @param string|integer|array|boolean|null $text - log text
     * @param string $title - log title
     */
    public static function writeError($text, $title = null)
    {
        self::write($text, $title, 'error');
    }
    
    /**
     * Write exception information log to error file
     *
     * @param exception $e - exception object
     * @param string $title - log title
     */
    public static function writeException(Exception $e, $title = null)
    {
        $log = self::getDateTime()."[".$e->getFile().":".$e->getLine()."] ".$e->getMessage()."\n";
        self::writeLog($log, $title, 'error');
    }
    
    /**
     * Clear log file with current date
     */
    public static function clear($filename = null)
    {
        $fpath = self::filePath($filename);
        file_put_contents($fpath, '');
    }
    
    /**
     * Get file path
     */
    private static function filePath($filename = null)
    {
        $filename = ($filename==null) ? date('d.m.Y').".log.txt" : date('d.m.Y').".".$filename.".log.txt";
        if (!empty(self::$path)) {
            return __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.self::$path.DIRECTORY_SEPARATOR.$filename;
        } else {
            return  __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$filename;
        }
    }
    
    /**
     * Write a border to log file
     * @param string $title - border title
     */
    public static function border($title = 'BORDER')
    {
        self::writeLog(str_repeat("/", 10).$title.str_repeat("/", 80)."\n");
    }
    
    private function buildTree($array, $tabsnum = 1)
    {
        $log = "";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $log.= str_repeat("\t", $tabsnum)."[{$key}]=> ".self::getDataDump($value)."\n";
                $log.= self::buildTree($value, ($tabsnum + 1));
            } else {
                $log.= str_repeat("\t", $tabsnum)."[{$key}]=> ".self::getDataDump($value)."\n";
            }
        }
        return $log;
    }
    
    private static function renderArray($array, $title)
    {
        $log = self::getDataDump($array)."\n";
        $log.= self::buildTree($array);
        return self::getDateTime()."".self::getTtitle($title)."".self::getCaller()." ".$log;
    }
        
    private static function renderString($text, $title)
    {
        $log = self::getDateTime()."".self::getTtitle($title)."".self::getCaller()." ".self::getDataDump($text)."\n";
        return $log;
    }
    
    private static function getDataDump($data)
    {
        $type = gettype($data);
        if ($type=='string') {
            return "string(".strlen($data).") \"".$data."\"";
        } elseif ($type=='array') {
            return "array(".count($data).")";
        } elseif ($type=='integer') {
            return "integer(".$data.")";
        } elseif ($type=='NULL') {
            return "NULL";
        } elseif ($type=='boolean') {
            if ($data) {
                return "boolean(true)";
            } else {
                return "boolean(false)";
            }
        } else {
            return " ".$data." ";
        }
    }
    
    private static function getCaller()
    {
        if(!self::$show_caller) return null;
        $info = debug_backtrace();
        $last = end($info);
        $log = "[".$last['file']." line:".$last['line']."]";
        if ($last['class']!="LOG") {
            $log.="[class:".$last['class']."]";
        }
        return $log;
    }

    private static function getTtitle( $title )
    {
        if ($title!=null) {
           return "[{$title}]";
        }
        return null;   
    }
    
    private static function getDateTime()
    {
        return date('[Y.m.d][H:i:s]');
    }
    
    private static function writeLog($text, $extra = null, $filename = null)
    {
        $fpath = self::filePath($filename); echo $fpath;
        $fd = fopen($fpath, 'a+') or die("LOG: failed to write log!");        
        fwrite($fd, $text);
        fclose($fd);
    }
}