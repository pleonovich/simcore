<?php
/**
 * DO INPUT CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to render html form inputs
 *
 * Some examples:
 *
 * $input = DoInput::text('name');
 * $input = DoInput::textarea('biography');
 * $input = DoInput::select('gender', array('male','famale'), 'famale');
 * $input = DoInput::selectIndexed('gender', array('1'=>'famale','2'=>'male'), '2');
 * $input = DoInput::selectFormated('gender', array(array('1','famale'),array('2','male')), '2');
 * $input = DoInput::checkbox('maried');
 * $input = DoInput::_file('avatar');
 * $input = DoInput::submit('Send');
 *
 */

class DoInput
{
    
    /**
     * Input type text
     *
     * @param string $name - input title
     * @param string $value - default value
     * @param string $extra - anything you need to put into input tag
     * @return html input type select
     */
    public static function text($name, $value = null, $extra = null)
    {
        return "<input type='text' id='".$name."' name='".$name."' ".$extra." value='".$value."' >";
    }
    
    /**
     * Textarea
     *
     * @param string $name - input title
     * @param string $value - default value
     * @param int $cols - cols value
     * @param int $rows - rows value
     * @param string $extra - anything you need to put into input tag
     * @return html input type select
     */
    public static function textarea($name, $value = null, $cols = 50, $rows = 3, $extra = null)
    {
        return "<textarea id='".$name."' name='".$name."' cols='".$cols."' rows='".$rows."' ".$extra." >".$value."</textarea>";
    }

    /**
     * Select with simple arrays
     *
     * @param string $name - input title
     * @param array $options - select options array, like - array('famale','male')
     * @param string $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @param string $default - value of default option
     * @return string - html input type select
     */
    public static function select($name, array $options, $value = null, $extra = null, $default = null)
    {
        $select = "<select name='".$name."' id='select_".$name."' size='1' ".$extra." >\n";
        if ($default!=null) {
            $select .= "<option  value='' >".$default."</option>\n";
        }
        foreach ($options as $one) {
            if ($value==$one) {
                $selected = "selected";
            } else {
                $selected = null;
            }
            $select .= "<option  value='".$one."' ".$selected." >".$one."</option>\n";
        }
        $select .= "</select>";
        return $select;
    }

    /**
     * Select with formated arrays
     *
     * @param string $name - input title
     * @param array $options - select options formated array, like - array(array('1','famale'),array('2','male'))
     * @param boolean $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @param string $default - value of default option
     * @return html input type select
     */
    public static function selectFormated($name, array $options, $value = null, $extra = null, $default = null)
    {
        $select = "<select name='".$name."' id='select_".$name."' size='1' ".$extra." >\n";
        if ($default!=null) {
            $select .= "<option  value='' >".$default."</option>\n";
        }
        foreach ($options as $one) {
            if (in_array($value, $one)) {
                $selected = "selected";
            } else {
                $selected = null;
            }
            $select .= "<option  value='".$one[0]."' ".$selected." >".$one[1]."</option>\n";
        }
        $select .= "</select>";
        return $select;
    }
    
    /**
     * Select with indexed arrays
     *
     * @param string $name - input title
     * @param array $options - select options indexed array, like - array('1'=>'famale','2'=>'male')
     * @param string $value - selected value
     * @param string $extra - anything you need to put into input tag
     * @param string $default - value of default option
     * @return html input type select
     */
    public static function selectIndexed($name, $options, $value = null, $extra = null, $default = null)
    {
        $select = "<select name='".$name."' id='select_".$name."' size='1' ".$extra." >\n";
        if ($default!=null) {
            $select .= "<option  value='' >".$default."</option>\n";
        }
        foreach ($options as $key => $title) {
            $selected = ($key==$value) ? "selected" : null;
            $select .= "<option  value='".$key."' ".$selected." >".$title."</option>\n";
        }
        $select .= "</select>";
        return $select;
    }

    /**
     * Input type checkbox
     *
     * @param string $name - input title
     * @param string $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @return html input type chekbox
     */
    public static function checkbox($name, $value = null, $extra = null)
    {
        $chek_box = "";
        $checked = (in_array($value, array('1','true','on'))) ? "checked='checked'" : null;
        return "<input type='checkbox' id='".$name."' name='".$name."' ".$checked." ".$extra." />";
    }

    /**
     * Input type file
     *
     * @param string $name - input title
     * @param boolean $multiple - multiple input or not
     * @param string $extra - anything you need to put into input tag
     * @return html input type file
     */
    public static function _file($name = "file", $multiple = true, $extra = null)
    {
        $multiple = ($multiple) ? 'true' : 'false' ;
        return "<input type='file' name='".$name."[]' multiple='".$multiple."' ".$extra.">";
    }
    
    /**
     * Input type submit
     *
     * @param string $name - input title
     * @param string $extra - anything you need to put into input tag
     * @return html input type submit
     */
    public static function submit($name, $extra = null)
    {
        return "<input type='submit' name='".$name."' ".$extra." >";
    }
}