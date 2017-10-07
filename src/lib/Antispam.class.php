<?php
///////////////////////////
// ANTISPAM CLASS 1.0.0 //
/////////////////////////

/**
 * @author zavorokhina.yuliya@gmail.com
 * Filter for cover spam links.
 *
 * Key features:
 * - Fully covers the spam links.
 *
 * Some examples:
 *
 * Antispam::replace('Array of indexes in $_POST');
 *
 */
 
class Antispam {

		const REPLACEMENT = '***';

		public static function cleanPost( ) {
			$data = func_get_args();
			foreach($data as $index) {
				if(isset($_POST[$index])) {
					$_POST[$index] = self::replace($_POST[$index]);
				}
			}
	    }

		public static function replace ( $string ) {
			$pattern = "!<a.*?href=\"?'?([^ \"'>]+)\"?'?.*?>(.*?)</a>!is";
			return	preg_replace($pattern, self::REPLACEMENT, $string);
		}
		
}