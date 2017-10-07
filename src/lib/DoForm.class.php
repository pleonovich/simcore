<?php
/**
 * DO FORM CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to render html forms
 *
 * Example:
 *
 * $form = DoForm::factory()
 * ->addText('name')
 * ->addTextarea('biography')
 * ->addSelect('gender', array('male','famale'))
 * ->addSelectIndexed('gender1', array('1'=>'famale','2'=>'male'), '2')
 * ->addSelectFormated('gender2', array(array('1','famale'),array('2','male')), '2')
 * ->addCheckbox('maried')
 * ->addFile('avatar')
 * ->addSubmit('Send')
 * ->toArray();
 *
 */

require_once('DoInput.class.php');

class DoForm extends DoInput
{
    
    private $Form = array();
    
    function __construct()
    {
    }
    
    /**
     * Input type text
     *
     * @param string $name - input title
     * @param string $value - default value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addText($name, $value = null, $extra = null)
    {
        $this->Form[$name] = self::text($name, $value, $extra);
        return $this;
    }
    
    /**
     * Input type password
     *
     * @param string $name - input title
     * @param string $value - default value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addPassword($name, $value = null, $extra = null)
    {
        $this->Form[$name] = self::password($name, $value, $extra);
        return $this;
    }
    
    /**
     * Textarea
     *
     * @param string $name - input title
     * @param string $value - default value
     * @param int $cols - cols value
     * @param int $rows - rows value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addTextarea($name, $value = null, $cols = 50, $rows = 3, $extra = null)
    {
        $this->Form[$name] = self::textarea($name, $value, $cols, $rows, $extra);
        return $this;
    }
    
    /**
     * Select with simple arrays
     *
     * @param string $name - input title
     * @param array $options - select options array, like - array('male','famale')
     * @param string $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addSelect($name, array $options, $value = null, $extra = null)
    {
        $this->Form[$name] = self::select($name, $options, $value, $extra);
        return $this;
    }
    
    /**
     * Select with formated arrays
     *
     * @param string $name - input title
     * @param array $options - select options formated array, like - array(array('1','famale'),array('2','male'))
     * @param boolean $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addSelectFormated($name, array $options, $value = null, $extra = null)
    {
        $this->Form[$name] = self::selectFormated($name, $options, $value, $extra);
        return $this;
    }
    
    /**
     * Select with indexed arrays
     *
     * @param string $name - input title
     * @param array $options - select options indexed array, like - array('1'=>'famale','2'=>'male')
     * @param string $selected - selected value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addSelectIndexed($name, array $options, $value = null, $extra = null)
    {
        $this->Form[$name] = self::selectIndexed($name, $options, $value, $extra);
        return $this;
    }
    
    /**
     * Input type checkbox
     *
     * @param string $name - input title
     * @param string $value - checked value
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addCheckbox($name, $value = null, $extra = null)
    {
        $this->Form[$name] = self::checkbox($name, $value, $extra);
        return $this;
    }
    
    /**
     * Input type file
     *
     * @param string $name - input title
     * @param boolean $multiple - multiple input or not
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addFile($name = "file", $multiple = true, $extra = null)
    {
        $this->Form[$name] = self::_file($name, $multiple, $extra);
        return $this;
    }
    
    /**
     * Input type submit
     *
     * @param string $name - input title
     * @param string $extra - anything you need to put into input tag
     * @return SimpleForm - this object
     */
    public function addSubmit($name, $extra = null)
    {
        $this->Form['submit'] = self::submit($name, $extra);
        return $this;
    }

    /**
     * Get form element by name
     */
    public function __get ($name) {
        if(isset($this->Form[$name])) return $this->Form[$name];
        else return null;
    }

    /**
     * Render form to string
     */
    public function __toString()
    {
        return implode("\n", $this->Form);
    }
    
    /**
     * Render form to array
     */
    public function toArray()
    {
        return $this->Form;
    }
    
    public static function factory()
    {
        return new DoForm ();
    }
}