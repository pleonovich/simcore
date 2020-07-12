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

	public static function transLatEng($str) {
		$mm = strlen($str);
		$TEXT = "";
		// echo "str = ".$str." / mm = ".$mm."<br>";
		for ($i = 0; $i < $mm; $i++) {
			$ss = mb_substr($str, $i, 1, "utf-8");
			// echo "i=".$i." / ss=".$ss."<br>";
			switch ($ss) {
				case "#":
					$TEXT = $TEXT."";
					break;
				case "¹":
					$TEXT = $TEXT."";
					break;
				case "+":
					$TEXT = $TEXT."";
					break;
				case "(":
					$TEXT = $TEXT."";
					break;
				case ")":
					$TEXT = $TEXT."";
					break;
				case "\"":
					$TEXT = $TEXT."";
					break;
				case "?":
					$TEXT = $TEXT."";
					break;
				case ".":
					$TEXT = $TEXT."";
					break;
				case ",":
					$TEXT = $TEXT."";
					break;
				case "/":
					$TEXT = $TEXT."";
					break;
				case "а":
					$TEXT = $TEXT."a";
					break;				
				case "А":
					$TEXT = $TEXT."A";
					break;				
				case "б":
					$TEXT = $TEXT."b";
					break;				
				case "Б":
					$TEXT = $TEXT."B";
					break;				
				case "в":
					$TEXT = $TEXT."v";
					break;				
				case "В":
					$TEXT = $TEXT."V";
					break;				
				case "г":
					$TEXT = $TEXT."g";
					break;				
				case "Г":
					$TEXT = $TEXT."G";
					break;				
				case "д":
					$TEXT = $TEXT."d";
					break;				
				case "Д":
					$TEXT = $TEXT."D";
					break;				
				case "е":
					$TEXT = $TEXT."e";
					break;				
				case "Е":
					$TEXT = $TEXT."E";
					break;				
				case "ё":
					$TEXT = $TEXT."ye";
					break;				
				case "Ё":
					$TEXT = $TEXT."Ye";
					break;				
				case "ж":
					$TEXT = $TEXT."zh";
					break;				
				case "Ж":
					$TEXT = $TEXT."Zh";
					break;				
				case "з":
					$TEXT = $TEXT."z";
					break;				
				case "З":
					$TEXT = $TEXT."Z";
					break;				
				case "и":
					$TEXT = $TEXT."i";
					break;				
				case "И":
					$TEXT = $TEXT."I";
					break;				
				case "й":
					$TEXT = $TEXT."y";
					break;				
				case "Й":
					$TEXT = $TEXT."Y";
					break;				
				case "к":
					$TEXT = $TEXT."k";
					break;				
				case "К":
					$TEXT = $TEXT."K";
					break;				
				case "л":
					$TEXT = $TEXT."l";
					break;				
				case "Л":
					$TEXT = $TEXT."L";
					break;				
				case "м":
					$TEXT = $TEXT."m";
					break;				
				case "М":
					$TEXT = $TEXT."M";
					break;				
				case "н":
					$TEXT = $TEXT."n";
					break;				
				case "Н":
					$TEXT = $TEXT."N";
					break;				
				case "о":
					$TEXT = $TEXT."o";
					break;				
				case "О":
					$TEXT = $TEXT."O";
					break;				
				case "п":
					$TEXT = $TEXT."p";
					break;				
				case "П":
					$TEXT = $TEXT."P";
					break;				
				case "р":
					$TEXT = $TEXT."r";
					break;				
				case "Р":
					$TEXT = $TEXT."R";
					break;				
				case "с":
					$TEXT = $TEXT."s";
					break;				
				case "С":
					$TEXT = $TEXT."S";
					break;				
				case "т":
					$TEXT = $TEXT."t";
					break;				
				case "Т":
					$TEXT = $TEXT."T";
					break;				
				case "у":
					$TEXT = $TEXT."u";
					break;				
				case "У":
					$TEXT = $TEXT."U";
					break;				
				case "ф":
					$TEXT = $TEXT."f";
					break;				
				case "Ф":
					$TEXT = $TEXT."F";
					break;				
				case "х":
					$TEXT = $TEXT."h";
					break;				
				case "Х":
					$TEXT = $TEXT."H";
					break;				
				case "ц":
					$TEXT = $TEXT."c";
					break;				
				case "Ц":
					$TEXT = $TEXT."C";
					break;				
				case "ч":
					$TEXT = $TEXT."ch";
					break;				
				case "Ч":
					$TEXT = $TEXT."Ch";
					break;				
				case "ш":
					$TEXT = $TEXT."sh";
					break;				
				case "Ш":
					$TEXT = $TEXT."Sh";
					break;				
				case "щ":
					$TEXT = $TEXT."sch";
					break;				
				case "Щ":
					$TEXT = $TEXT."Sch";
					break;				
				case "ь":
					$TEXT = $TEXT."";
					break;				
				case "ъ":
					$TEXT = $TEXT."";
					break;				
				case "э":
					$TEXT = $TEXT."e";
					break;				
				case "Э":
					$TEXT = $TEXT."E";
					break;				
				case "ю":
					$TEXT = $TEXT."yu";
					break;				
				case "Ю":
					$TEXT = $TEXT."Yu";
					break;				
				case "я":
					$TEXT = $TEXT."ya";
					break;				
				case "Я":
					$TEXT = $TEXT."Ya";
					break;				
				default:
					$TEXT = $TEXT.$ss;
				}
			}
			$str = $TEXT;
			$str = strtolower($str);
			return $str;
		}

}