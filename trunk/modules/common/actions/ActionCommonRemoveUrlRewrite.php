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
 * ActionCommonRemoveUrlRewrite
 *
 * USAGE:
 * $model->action( 'common', 'removeUrlRewrite',      // one of the following param
 *                array('id_map'        => int        
                        'module'        => string,
                        'request_name'  => string) );    
 *
 */

class ActionCommonRemoveUrlRewrite extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['id_map']))
        {
            $sql_where = '`id_map`='.$this->model->dba->escape((int)$data['id_map']);
        }
        elseif(isset($data['module']))
        {
            $sql_where = "`module`='".$this->model->dba->escape($data['module'])."'";
        }        
        elseif(isset($data['request_name']))
        {
            $sql_where = "`request_name`='".$this->model->dba->escape($data['request_name'])."'";
        }        
        
        $sql = "DELETE FROM {$this->config->dbTablePrefix}common_public_controller_map
                   WHERE 
                   {$sql_where}";

        $this->model->dba->query($sql);      
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        return true;
    }    
}

?>