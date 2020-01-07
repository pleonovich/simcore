<?php 
namespace SimCore\lib;
/**
 * TextEdit 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with string data
 *
 * Some examples:
 *
 * $result = TextEdit::cutWords("Some text words", 2, 0);
 * // Some text
 * $result = TextEdit::countWords("Some text words");
 * // 3
 * $result = TextEdit::wordsSet("Some text words");
 * // [ "Some", "text", "words" ]
 * $result = TextEdit::transLatEngFname("НазваниеФайла.txt");
 * $result = TextEdit::transLatEngUrl("название/НазваниеФайла.txt");
 *
 */

class TextEdit {

	public static function cutWords ( $text, $numofw, $startfw=0 ) {	
		$text = strip_tags($text);
		$text = htmlspecialchars($text); 
		$input_text_size = self::countWords($text);
		$array = explode(" ",$text); 
		$array = array_slice($array,$startfw,$numofw); 
		$newtext = implode(" ",$array); 
		$output_text_size = self::countWords($newtext);
		if ($output_text_size < $input_text_size) {
			$newtext .= " ...";
		}
		return $newtext;
	}

	public static function countWords ( $text ) {
		$words_num = 0;
		$text_rows = explode(" ", $text);
		$words_num = count($text_rows);
		return $words_num;
	}

	public static function wordsSet ( $text ) {
		$array = explode(" ",$text); // convertion string to array
		$words_set = implode(", ",$array); // array convertion back to string
		if (self::countWords($text) > 1) {
			if (substr($words_set,-1) == ",") {
				$words_set = substr($words_set,0,-1);
				}
			}
		return $words_set;
	}
	
	public static function transLatEngFname ( $str ) {
		$str = stripslashes($str);
		$str = htmlspecialchars($str,ENT_QUOTES);
		$str = preg_replace('| +|', '', $str);
		$str = str_replace(array("'","\""), '', $str);
		$str = self::transLatEng($str);
		return $str;
	}

	public static function transLatEngUrl ( $str ) {
		$str = strip_tags($str);
		$str = stripslashes($str);
		//$str = htmlspecialchars($str,ENT_QUOTES);
		$str = preg_replace('| +|', '_', $str);
		$str = str_replace(array(" - ","---"), '-', $str);
		$str = str_replace(array("?","!",";", ",", ".","'","\""), '', $str);
		$str = self::transLatEng ($str);
		return $str;
	}

	public static function transLatEng ( $str ) {
		$replace = array(
			"&"=>"",
			"а"=>"a","А"=>"a",
			"б"=>"b","Б"=>"b",
			"в"=>"v","В"=>"v",
			"г"=>"g","Г"=>"g",
			"д"=>"d","Д"=>"d",
			"е"=>"e","Е"=>"e",
			"ж"=>"zh","Ж"=>"zh",
			"з"=>"z","З"=>"z",
			"и"=>"i","И"=>"i",
			"й"=>"y","Й"=>"y",
			"к"=>"k","К"=>"k",
			"л"=>"l","Л"=>"l",
			"м"=>"m","М"=>"m",
			"н"=>"n","Н"=>"n",
			"о"=>"o","О"=>"o",
			"п"=>"p","П"=>"p",
			"р"=>"r","Р"=>"r",
			"с"=>"s","С"=>"s",
			"т"=>"t","Т"=>"t",
			"у"=>"u","У"=>"u",
			"ф"=>"f","Ф"=>"f",
			"х"=>"h","Х"=>"h",
			"ц"=>"c","Ц"=>"c",
			"ч"=>"ch","Ч"=>"ch",
			"ш"=>"sh","Ш"=>"sh",
			"щ"=>"sch","Щ"=>"sch",
			"ъ"=>"","Ъ"=>"",
			"ы"=>"y","Ы"=>"y",
			"ь"=>"","Ь"=>"",
			"э"=>"e","Э"=>"e",
			"ю"=>"yu","Ю"=>"yu",
			"я"=>"ya","Я"=>"ya",
			"і"=>"i","І"=>"i",
			"ї"=>"yi","Ї"=>"yi",
			"є"=>"e","Є"=>"e"
		);
		$str = strtolower($str);
		return strtr($str, $replace);
	}
}