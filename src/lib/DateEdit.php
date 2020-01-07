<?php
namespace SimCore\lib;
/**
 * DATE EDIT CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to edit date data
 *
 * Key features:
 * - date validation;
 * - date format validation;
 * - date format convertion;
 * - easy date to unix convertion;
 * - easy change year, month, week, day or hour.
 *
 * Some examples:
 *
 * $valid = DateEdit::validate("2017.02.03","Y.m.d");
 * $check = DateEdit::checkFormat("2017.02.03","Y.m.d");
 * $date = DateEdit::convertFormat("2017.02.03","Y.m.d","d-m-Y");
 * $date = DateEdit::dateToUnix("2017.06.18","Y.m.d");
 * $date = DateEdit::unixToDate(1497744000,"Y.m.d");
 * $date = DateEdit::changeYear("2017.02.03", "Y.m.d", 1, '+');
 * $date = DateEdit::changeMonth("2017.06.17", "Y.m.d", 1, '+');
 * $date = DateEdit::changeWeek("2017.06.17", "Y.m.d", 1, '+');
 * $date = DateEdit::changeDay("2017.06.17", "Y.m.d", 1, '+');
 * $date = DateEdit::changeHours("2017.06.17 23:39:00", "Y.m.d H:i:s", 1, '+');
 *
 */
 
class DateEdit
{
    
    /**
     * Date validation
     *
     * @param string $date - date
     * @param string $format - date format
     * @result boolean - validation result
     */
    public static function validate($date, $format)
    {
        if(empty($date)) {
            return false;
        }
        $datemaxnum = array("Y"=>'9999',"m"=>'12',"d"=>'31',"H"=>'24',"i"=>'60',"s"=>'60');
        $datedata = self::parseDate($date, $format);
        foreach ($datedata as $key => $value) {
            if ($value > $datemaxnum[$key]) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Checking date format
     *
     * @param string $date - date
     * @param string $format - date format
     * @result boolean - date format checking result
     */
    public static function checkFormat($date, $format)
    {
        if ($date=="") {
            return false;
        }
        $delimiter = ",";
        $date_data = self::parseDate($date, $format);
        $date_resdels = self::resetDelimiters($date, $delimiter);
        $format_resdels = self::resetDelimiters($format, $delimiter);
        $date_exp = explode($delimiter, $date_resdels);
        $format_exp = explode($delimiter, $format_resdels);
        if (count($date_exp) == count($format_exp) && self::validate($date, $format)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Date format conversion
     *
     * @param string $date - date
     * @param string $informat - date input format
     * @param string $outformat - date output format
     * @result boolean - date with new format
     */
    public static function convertFormat($date, $informat, $outformat)
    {
        if ($date=="") {
            return "";
        }
        $date_array = self::parseDate($date, $informat);
        return self::prepareString($date_array, $outformat);
    }
    
    /**
     * Date format conversion to unix
     *
     * @param string $date - date
     * @param string $informat - date input format
     * @result int - date in unix format
     */
    public static function dateToUnix($date, $format)
    {
        $date = self::parseDate($date, $format);
        return mktime($date['H'], $date['i'], $date['s'], $date['m'], $date['d'], $date['Y']);
    }

    /**
     * Date format conversion from unix to string
     *
     * @param string $format - date input format
     * @param string $unix - unix time
     * @result string - date string
     */
    public static function unixToDate($unix, $format)
    {
        return date( $format, $unix );
    }

    /**
     * Change date
     *
     * @param string $date - date
     * @param int $NUM - multiply period number
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    private static function changeDate($date, $format, $period = 'day', $NUM = 1, $direction = '+')
    {
        $date = self::convertFormat($date, $format, 'Y-m-d H:i:s');
        return date($format, strtotime($direction.$NUM." ".$period, strtotime($date)));
    }

    /**
     * Change year
     *
     * @param string $date - date
     * @param int $yearN - number of years
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    public static function changeYear($date, $format, $yearN = 1, $direction = '+')
    {
        return self::changeDate($date, $format, 'year', $yearN, $direction);
    }

    /**
     * Change month
     *
     * @param string $date - date
     * @param int $yearN - number of month
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    public static function changeMonth($date, $format, $monthN = 1, $direction = '+')
    {
        return self::changeDate($date, $format, 'month', $monthN, $direction);
    }

    /**
     * Change week
     *
     * @param string $date - date
     * @param int $yearN - number of weeks
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    public static function changeWeek($date, $format, $weekN = 1, $direction = '+')
    {
        return self::changeDate($date, $format, 'week', $weekN, $direction);
    }

    /**
     * Change day
     *
     * @param string $date - date
     * @param int $yearN - number of days
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    public static function changeDay($date, $format, $dayN = 1, $direction = '+')
    {
        return self::changeDate($date, $format, 'day', $dayN, $direction);
    }

    /**
     * Change hour
     *
     * @param string $date - date
     * @param int $yearN - number of hours
     * @param string $direction - direction
     * @param string $format - unix time
     * @result string - new date
     */
    public static function changeHours($date, $format, $hourN = 1, $direction = '+')
    {
        return self::changeDate($date, $format, 'hour', $hourN, $direction);
    }
        
    private static function parseDate($date, $format, $delimiter = null)
    {
        $newdate = array("Y"=>"0000","m"=>'00',"d"=>'00',"H"=>'00',"i"=>'00',"s"=>'00');
        if ($delimiter===null) {
            $delimiter = ",";
            $date_resdels = self::resetDelimiters($date, $delimiter);
            $format_resdels = self::resetDelimiters($format, $delimiter);
        }
        $date_exp = explode($delimiter, $date_resdels);
        $format_exp = explode($delimiter, $format_resdels);
        foreach ($format_exp as $key => $value) {
            if (isset($date_exp[$key])) {
                $newdate[$value] = $date_exp[$key];
            }
        }
        return $newdate;
    }
    
    private static function resetDelimiters($date, $delimiter = ",")
    {
        $replace = array($delimiter, $delimiter, $delimiter, $delimiter);
        $pattern = array("/\-/", "/ /","/\:/","/\./");
        return preg_replace($pattern, $replace, $date);
    }

    private static function prepareString($date_array, $format)
    {
        $replace = array($date_array['Y'],$date_array['m'],$date_array['d'],$date_array['H'],$date_array['i'],$date_array['s']);
        $pattern = array("/Y/","/m/","/d/","/H/","/i/","/s/");
        return preg_replace($pattern, $replace, $format);
    }
}