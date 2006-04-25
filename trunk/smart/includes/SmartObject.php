<?php
/**
 * SmartObject that all Smart classes inherit.
 */
abstract class SmartObject
{
    /**
     * Retrieve a string representation of this object.
     */
    public function __toString ()
    {
        $vars = get_object_vars($this);
        $return_str = '';
        
        foreach($vars as $key => $val)
        {
            $return_str .= $key . " = " . $val . "\n";
        }
        
        return $return_str;
    }
}

?>
