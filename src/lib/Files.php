<?php
/**
 * FILES CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with files and images
 *
 *
 */

class Files
{
    public static $MESSAGE = "";
    public static $IMG_DIR = "images/user";
    
    public static $file_types_desc = array(
        "db"=>"data base",
        "exe"=>"exe file",
        "pdf"=>"PPDF document",
        "swf"=>"Flash document",
        "doc"=>"Word document",
        "docx"=>"Word document",
        "csv"=>"CSV document",
        "xls"=>"Excel document",
        "xlsx"=>"Excel document",
        "txt"=>"Text document",
        "zip"=>"ZIP archive",
    );
    
    public static function resize($file_input, $file_output, $w_o, $h_o, $percent = false)
    {
        list($w_i, $h_i, $type) = getimagesize($file_input);
        if (!$w_i || !$h_i) {
            self::$MESSAGE.= "<div id='MESAGE_bad'>Невозможно получить длину и ширину изображения</div>";
            return;
        }
            $types = array('','gif','jpeg','png');
            $ext = $types[$type];
        if ($ext) {
            $func = 'imagecreatefrom'.$ext;
            $img = $func($file_input);
        } else {
            self::$MESSAGE.= "<div id='MESAGE_bad'Некорректный формат файла</div>";
            return;
        }
        if ($percent) {
            $w_o *= $w_i / 100;
            $h_o *= $h_i / 100;
        }
        if (!$h_o) {
            $h_o = $w_o/($w_i/$h_i);
        }
        if (!$w_o) {
            $w_o = $h_o/($h_i/$w_i);
        }

        $img_o = imagecreatetruecolor($w_o, $h_o);
        imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
        if ($type == 1) {
            return imagegif($img_o, $file_output);
        } else if ($type == 2) {
            return imagejpeg($img_o, $file_output, 60);
        } else if ($type == 3) {
            return imagepng($img_o, $file_output, 6);
        }
    }
    


    public static function upload( $index, $path, $change_name=false ) {
        $blacklist = array("php", "phtml", "php3", "php4");
        $allow = array("image/gif","image/png","image/jpg","image/jpeg");
        $maxwidth = '2000';
        $maxheight = '2000';
        $files = array();
        
        // LOG::write($_POST, '_POST');
        // LOG::write($_FILES, '_FILES');


        if (isset($_FILES[$index])){
            foreach ($_FILES[$index]['name'] as $k=>$v) {

                $fname = $_FILES[$index]['name'][$k];
                $ftype = $_FILES[$index]['type'][$k];
                $fext = substr(strrchr($fname, '.'), 1);
                // LOG::write($fname, 'fname');
                // LOG::write($ftype, 'ftype');
                // LOG::write($fext, 'fext');

                if($change_name) $fname = date('YmdHis').rand(100,1000).'.'.$fext;

                $uploadfile = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$fname;
                //LOG::write($uploadfile, 'uploadfile');
                if(in_array($ftype, $allow)) {

                    if(in_array($fname, $blacklist)) { 
                        static::$MESSAGE = "Нельзя загружать скрипты."; 
                        $TYPE = 0; 
                        exit;
                    }

                    if (move_uploaded_file($_FILES[$index]['tmp_name'][$k], $uploadfile)) {                        
                        $size = getimagesize($uploadfile);                        
                        if ($size[0] > $maxwidth && $size[1] > $maxheight) {
                            static::$MESSAGE = "Размер пикселей превышает допустимые нормы."; $TYPE = 0; 
                            unlink($uploadfile);
                        } else {
                            $files[] = $fname;
                        }
                    } else static::$MESSAGE = "Файл не загружен, вернитесь и попробуйте еще раз."; $TYPE = 0;
                } else static::$MESSAGE = "Можно загружать только изображения в форматах jpg, jpeg, gif и png."; $TYPE = 0;
            }
        }
        if(count($files)>0) static::$MESSAGE = "Успешно загружено";
        LOG::write($files, 'files');
        LOG::write(static::$MESSAGE, 'MESSAGE');
        return $files;
    }


    public static function count($path)
    {
        $countFiles = 0;
        $dir = scandir($_SERVER['DOCUMENT_ROOT']."/".$path);
        foreach ($dir as $file) {
            if (in_array($file, array('.','..'))) {
                continue;
            }
            $countFiles += 1;
        }
        return $countFiles;
    }	

	public static function imgsize($url)
    {
        $data = @getimagesize($_SERVER['DOCUMENT_ROOT']."/".$url);
        return $data[0]."x".$data[1]." Px";
    }

    public static function size($url)
    {
        $filesize = filesize($_SERVER['DOCUMENT_ROOT']."/".$url) / 1024;
        $filesize = round($filesize, 1);
        if ($filesize > 1000) {
            $filesize = round($filesize/1024, 1);
            return $filesize." Mb";
        } else {
            return $filesize." Kb";
        }
    }
    
    public static function dirsize($url)
    {
        //echo "path=".$path."<br>";
        $path = $_SERVER['DOCUMENT_ROOT']."/".$url;
        if (is_file($path)) {
            return filesize($path);
        }
        $size = 0;
        $dh = opendir($path);
        while (($file=readdir($dh))!==false) {
            if ($file=='.' || $file=='..') {
                continue;
            }
            if (is_file($path.'/'.$file)) {
                $size += filesize($path.'/'.$file);
            }
        }
        closedir($dh);
        $size = $size + filesize($path);
        $size = $size / 1024;
        $size = round($size, 1);
        if ($size > 1024) {
            $size = round($size/1024, 1)." Mb";
        } else {
            $size = $size." Kb";
        }
        return $size;
    }
    
    public static function get($dir)
    {
        $DIR_CONTENT = array();
        $invisible = array("..",".","thumbs");
        $files = scandir($dir);
        foreach($files as $f) {
            if (!in_array($f, $invisible)) {
                $DIR_CONTENT[] = $f;
            }
        }
        sort($DIR_CONTENT);
        return $DIR_CONTENT;
    }

    public static function info($file, $path, $prefix = null)
    {   
        $special = array('__blog_images','photogallery');
        $type = strtolower(substr(strrchr($file, '.'), 1));
        $info = array("file"=>"","path"=>"","info"=>"","size"=>"","browser"=>"","url"=>"");
        $domen = "http://".$_SERVER['HTTP_HOST']."/";
        $root = $_SERVER['DOCUMENT_ROOT']."/";
        $url = self::$IMG_DIR."/".$path."/".$prefix.$file;
        $link = $domen."/admin/index.php?mod=browse_images";
        
        $info['size'] = self::size($url);
        if ($type=="") {
            LOG::write($file,'file');
            $info['src'] = (in_array($file, $special)) ? $domen."admin/icons/icon-sfolder.jpg" : $domen."admin/icons/icon-folder.jpg";
            $info['info'] = self::count($url)." Files";
            $info['size'] = self::dirsize($url);
            LOG::write($path,'path');
            if (empty($path)) {
                $info['url'] = $link."&folder=".$file;
            } else {
                $info['url'] = $link."&folder=".$path."/".$file;
            }
        } elseif (in_array($type, array("png","jpeg","jpg","gif"))) {
            $info['src'] = $domen.self::$IMG_DIR."/".$path."/".$file;
            $info['info'] = self::imgsize($url);
            $info['size'] = self::size($url);
        } else {
            $path = "admin/icons/icon-".$type.".jpg";
            $info['src'] = (file_exists($root.$path)) ? $domen.$path : $domen."admin/icons/icon-undefined.jpg";
            $info['info'] = (isset(self::$file_types_desc[$type])) ? self::$file_types_desc[$type] : "Undefined";
            $info['size'] = self::size($url);
        }
        //$info['size'] = $url;
        return $info;
    } 

}