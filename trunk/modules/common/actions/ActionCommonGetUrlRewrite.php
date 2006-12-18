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
 * ActionCommonGetUrlRewrite
 *
 * USAGE:
 * $model->action( 'common', 'getUrlRewrite',     
 *                array('result'        => array
                        'id_map'        => int        
                        'module'        => string,
                        'request_name'  => string,
                        'request_value' => int) );    
 *
 */

class ActionCommonGetUrlRewrite extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $_and = '';
        $sql_where = '';
        
        if(isset($data['id_map']))
        {
            $sql_where .= "`id_map`='".$this->model->dba->escape($data['id_map'])."'";
            $_and = " AND ";
        }
        
        if(isset($data['module']))
        {
            $sql_where .= $_and. "`module`='".$this->model->dba->escape($data['module'])."'";
            $_and = " AND ";
        }        
        
        if(isset($data['request_name']))
        {
            $sql_where .= $_and . "`request_name`='".$this->model->dba->escape($data['request_name'])."'";
            $_and = " AND ";
        }  
        
        if(isset($data['request_value']))
        {
            $sql_where .= $_and . "`request_value`=".$this->model->dba->escape((int)$data['request_value']);
        }     
        
        $sql = "SELECT * FROM {$this->config->dbTablePrefix}common_public_controller_map
                   WHERE 
                   {$sql_where}";

        $this->model->dba->query($sql);  
        
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
        }
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
        }
        
        return true;
    }    
}

?>