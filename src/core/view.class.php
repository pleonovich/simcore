<?php
/**
 * VIEW CLASS
 * ==========
 * 
 * Example:
 * View::factory()
 * ->bind("content",$this->content)
 * ->render('layout');
 * 
 * @author leonovich.pavel@gmail.com
 * @version 3.0.0
 * 
 */

class View
{
    protected static $_global_params = array();
    public static $dir_tmpl = "src/views";
    private $template;
    private $extends;
    private $cache;
    private $params = array();
    private $blocks = array();
    protected $templater_preg = array(
    // {{render controller:method}}
    '/{{\s*render\s*([A-Za-z0-9_]+)\:([A-Za-z0-9_]+)\s*}}/' => '<?php $this->renderController(\'${1}\', \'${2}\'); ?>',
    // {{var name=value}}
    '/{{\s*var\s*([A-Za-z0-9_]+)\=(.*?)\s*}}/' => '<?php $${1} = ${2}; ?>',
    // {{include tpl}}
    '/{{\s*include\s*\({0,1}([$A-Za-z.0-9_\-]+)\){0,1}\s*}}/' => '<?php $this->includeView(\'${1}\'); ?>',
    // {{foreach $array as $key=>$value}}
    '/{{\s*foreach\s*([$A-Za-z.0-9_\-]+)\s*as\s*([$A-Za-z.0-9_\-]+)\s*=>\s*([$A-Za-z.0-9_\-]+)\s*}}/' => '<?php foreach(${1} as ${2}=>${3}): ?>',
    // {{foreach $array as $one}}
    '/{{\s*foreach\s*([$A-Za-z.0-9_\-]+)\s*as\s*([$A-Za-z.0-9_\-]+)\s*}}/' => '<?php foreach(${1} as ${2}): ?>',
    // {{if true }}
    '/{{\s*if\s*(.*?)\s*}}/' => '<?php if(${1}): ?>',
    // {{elseif true }}
    '/{{\s*elseif\s*(.*?)\s*}}/' => '<?php elseif(${1}): ?>',
    //{{ else }}
    '/{{\s*else\s*}}/' => '<?php else: ?>',
    // {{for ($i=0;$i<$num;$i++)}echo $foo;{/endfor}}
    '/{{\s*for\s*\((.*?);(.*?);(.*?)\)}}/' => '<? for(${1}; ${2}; ${3}): ?>',
    // {{/for}}, {{/if}}, {{/foreach}}
    '/{{\s*\/(for|if|foreach)\s*}}/' => '<?php end${1}; ?>',
    // {{ $var->value }}
    '/{{\s*([$A-Za-z0-9_]+)->([A-Za-z0-9_]+)\s*}}/' => '<?=${1}->${2}?>',
    // {{ $var.value }}
    '/{{\s*([$A-Za-z0-9_]+)\.([A-Za-z0-9_]+)\s*}}/' => '<?=${1}->${2}?>',
    // {{ Class::const }}
    '/{{([A-Za-z0-9_]+)\:\:([A-Za-z0-9_]+)}}/' => '<?=${1}::${2}?>',
    // {{ array[index]}}
    '/{{\s*([$A-Za-z0-9_]+)\[([A-Za-z0-9_]+)\]\s*}}/' => '<?=${1}[\'${2}\']?>',
    // {{ $varname }}, {{constname}}
    '/{{\s*([$A-Za-z0-9_]+)\s*}}/' => '<?=${1}?>'
    );
    
    /**
     * Construct
     *
     * @param string $dir - views directory
     */
    public function __construct($dir = null)
    {
        if ($dir!=null) {
            self::$dir_tmpl = $dir;
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
    public function render($file, $return = true)
    {
        $this->template = $_SERVER['DOCUMENT_ROOT']."/".self::$dir_tmpl."/".$file.".view.php";
        $this->cache = $_SERVER['DOCUMENT_ROOT']."/".self::$dir_tmpl."/cache/".$file.".view.php";
        
        if (!file_exists($this->template)) {
            die("Error! File '".$this->template."' doesn`t exsist");
        }

        $orig_time = filemtime($this->template);
        $cash_time = (!is_file($this->cache)) ? 0 : filemtime($this->cache);

        // if ($cash_time>$orig_time) {
        //      $text = $this->getCache();
        // } else {
            $text = $this->parse();
        // }
        if ($return) {
            return $text;
        } else {
            echo $text;
        }
    }

    private function getCache () {
        ob_start();
        extract(self::$_global_params);
        extract($this->params);
        include($this->cache);
        $text = ob_get_contents();
        ob_end_clean();
        return $text;
    }

    private function setCache ( $text ) {
        $f = fopen($this->cache, 'w');
        fwrite($f, $text);
        fclose($f);
    }

    private function build ($template) {
        //LOG::write($template, 'template');
        $handle = fopen($template, "r");
        $text = "";
        $isblock = false;
        $blockName = '';
        $extends = false;
        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            if(preg_match('/{{\s*include\s*block\s*\({0,1}([$A-Za-z.0-9_\-]+)\){0,1}\s*}}/',$buffer, $matches, PREG_OFFSET_CAPTURE)) {
                $block = $matches[1][0];
                $buffer = $this->getBlock($block);
                //LOG::write($this->getBlock($block), 'getBlock '.$block);                
            } else
            if(preg_match('/{{\s*extends\s*\({0,1}(\'{0,1}[$A-Za-z.0-9_\-]+\'{0,1})\){0,1}\s*}}/',$buffer, $matches, PREG_OFFSET_CAPTURE)) {
                $extends = $matches[1][0];
                continue;
            } elseif(preg_match('/{{\s*block\s*\({0,1}(\'{0,1}[$A-Za-z.0-9_\-]+\'{0,1})\){0,1}\s*}}/',$buffer, $matches, PREG_OFFSET_CAPTURE)) {
                $isblock = true;
                $blockName = $matches[1][0];
                continue;
            } elseif(preg_match('/{{\s*\/block\s*\({0,1}(\'{0,1}[$A-Za-z.0-9_\-]+\'{0,1})\){0,1}\s*}}/',$buffer, $matches, PREG_OFFSET_CAPTURE)) {
                $isblock = false;
                continue;
            }
            if($isblock) {
                $this->setBlock($blockName, $buffer);
            } else {
                $text.= $buffer;
            }
        }
        fclose($handle);
        //LOG::write($this->blocks, 'blocks');
        if($extends) $text = $this->build($_SERVER['DOCUMENT_ROOT']."/".self::$dir_tmpl."/".$extends.".view.php");
        return $text;
    }

    private function parse () {
        $text = $this->build($this->template);
        //$text = file_get_contents($this->template);
        foreach ($this->templater_preg as $preg => $replace) {
            $text = preg_replace($preg, $replace, $text);
        }
        $this->setCache($text);
        extract(self::$_global_params);
        extract($this->params);
        ob_start();
        eval('?>'.$text.'<?');
        $text = ob_get_contents();
        ob_end_clean();
        return $text;
    }

    private function getBlock ( $name ) {
        if(isset($this->blocks[$name])) return $this->blocks[$name];
        else return "";
    }

    private function setBlock ( $name, $content ) {
        if(!isset($this->blocks[$name])) $this->blocks[$name] = $content;
        else $this->blocks[$name].= $content;
    }

    private function includeView ( $file ) {
        $this->render($file);
    }

    private function renderController ( $name, $method ) {
        $name.= "Controller";
        if (class_exists($name)) {
            $MODULE = new $name();
            if (method_exists($MODULE, $method)) {
                $MODULE->$method();
            } else {
                die("Error! Method ".$method." is not found");
            }
        } else {
            die("Error! Class ".$name." is not found");
        }
    }
    
    final public static function factory($dir = null)
    {
        return new View($dir);
    }

}