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
        // first check if a logged user gmt offset is available
        if(isset($this->config['user_gmt']))
        {
            return (string)$this->config['user_gmt'];
        }
        // else get site default gmt offset
        elseif(isset($this->config['default_gmt']))
        {
            return (string)$this->config['default_gmt'];
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