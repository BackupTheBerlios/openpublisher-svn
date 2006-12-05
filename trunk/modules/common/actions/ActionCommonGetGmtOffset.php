<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ActionCommonGetGmtOffset
 *
 * USAGE:
 *      $gmtOffset = $this->model->action('common', 'getGmtOffset');
 *
 */

class ActionCommonGetGmtOffset extends JapaAction
{
    /**
     * Perform on the action call
     *
     * @param mixed $data
     * @return string gmt offset 
     */
    public function perform( $data = FALSE )
    {
        $_user_gmt    = $this->config->getVar('user_gmt');
        $_default_gmt = $this->config->getVar('default_gmt');
        
        // first check if a logged user gmt offset is available
        if(null !== $_user_gmt)
        {
            return (string)$_user_gmt;
        }
        // else get site default gmt offset
        elseif(null !== $_default_gmt)
        {
            return (string)$_default_gmt;
        }
        
        return '0';
    }
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    {      
        return true;
    }
}

?>