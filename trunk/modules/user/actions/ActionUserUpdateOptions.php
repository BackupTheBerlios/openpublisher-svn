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
 * ActionUserUpdateOptions class 
 *
 */
 
class ActionUserUpdateOptions extends smartAction
{
    /**
     * Array of user_config table fields and the format/allowed values of each
     */
    private $tblFields = array('file_size_max'  => 'Int',
                               'img_size_max'   => 'Int',
                               'force_format'   => 'Int',
                               'default_format' => 'Int',
                               'use_log'        => 'Int',
                               'thumb_width'    => 'Int');
                               
    /**
     * update user module options
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        
        $comma  = "";
        $fields = "";
        
        foreach($data as $key => $val)
        {
            $fields .= $comma."`".$key."`='".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_config SET $fields";

        $this->model->dba->query($sql);
    }
    
    /**
     * validate user data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        foreach($data as $key => $val)
        {
            // check if database fields exists
            if(!isset($this->tblFields[$key]))
            {
                throw new SmartModelException("user_config table field '".$key."' dosent exists!");
            }
        }

        return TRUE;
    }
}

?>
