<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * View helper class ViewHelperEscape
 * 
 * Usage:
 * $escaped_var = $helper->escape(var_to_escape  (string),
 *                                escape_function (string) // optional 'htmlspecialchars' or 'htmlentities'
 *                                );
 *
 * 
 */
 
  class ViewHelperEscape extends JapaViewHelper
  {
  	public function perform()
  	{
  		if(!isset($this->args[0]))
  		{
  			return null;
  		}
  		return $this->escape();
  	}

    private function escape()
    {
        if (isset($this->args[1]) && in_array($this->args[1], array('htmlspecialchars', 'htmlentities')))
        {
            return call_user_func($this->args[1], $this->args[0], ENT_COMPAT, $this->config->getModuleVar('common', 'charset'));
        }

		// default escape function
        return htmlentities($this->args[0], ENT_COMPAT, $this->config->getModuleVar('common', 'charset'));
    }
  }
 
?>
