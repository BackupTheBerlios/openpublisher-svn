<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * View helper class ViewHelperDateFormat
 * 
 * Usage:
 * $date = $helper->dateFormat(date  (string),
 *                         format   (string) // see: http://www.php.net/manual/en/function.strftime.php
 *                         );
 *
 * 
 */
  class ViewHelperDateFormat extends JapaViewHelper
  {
 
  	public function perform()
  	{
    	if($this->args[0] != '') 
    	{
        	return strftime($this->args[1], $this->make_time($this->args[0]));
    	} 
    	else 
    	{
        	return null;
    	}
  	}

    private function make_time( $string )
    {
    	if(empty($string)) 
    	{
        	$time = time();
    	} 
    	elseif (preg_match('/^\d{14}$/', $string)) 
    	{     
        	$time = mktime(substr($string, 8, 2),substr($string, 10, 2),substr($string, 12, 2),
            	           substr($string, 4, 2),substr($string, 6, 2),substr($string, 0, 4));
        
    	} 
    	elseif (is_numeric($string)) 
    	{
        	$time = (int)$string;
        
    	}
    	else 
    	{
        	$time = strtotime($string);
        	if ($time == -1 || $time === false) 
        	{
            	$time = time();
        	}
    	}
    	return $time;
    }


  }
?>
