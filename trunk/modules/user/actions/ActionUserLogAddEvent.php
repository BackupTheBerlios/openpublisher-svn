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
 * ActionUserLogAddEvent class 
 * 
 * USAGE:
 * 
 * $model->action('user','logAddEvent',
 *                array('type'    => int,
 *                      'id_item' => int,
 *                      'module'  => string,
 *                      'view'    => string,
 *                      'message' => string))
 *
 *
 * type values:
 * ------------
 * 0 = error
 * 1 = login
 * 2 = logout
 * 3 = modify
 * 4 = access
 *
 */

 
class ActionUserLogAddEvent extends JapaAction
{
    private $tblFields = array('module'  => 'String',
                               'type'    => 'Int',
                               'view'    => 'String',
                               'id_item' => 'Int',
                               'message' => 'String');
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    {       
        $sql = "INSERT INTO {$this->config->dbTablePrefix}user_log
                   (`id_session`,`logdate`)
                  VALUES
                   ({$this->config->getModuleVar('user','log_id_session')},'{$this->config->getVar('gmtDate')}')";

        $this->model->dba->query($sql);
        
        $data['id_log'] = $this->model->dba->lastInsertID();
        
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }  
        
        $sql = "INSERT INTO {$this->config->dbTablePrefix}user_log_info
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        // check if database fields exists
        foreach($data as $key => $val)
        {
            if(!isset($this->tblFields[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }
        
        if(isset($data['module']))
        {
            if(!is_string($data['module']))
            {
                throw new JapaModelException("'module' isnt from type string!");
            }
        }        
        if(isset($data['view']))
        {
            if(!is_string($data['view']))
            {
                throw new JapaModelException("'view' isnt from type string!");
            }
        }   
        if(isset($data['message']))
        {
            if(!is_string($data['message']))
            {
                throw new JapaModelException("'message' isnt from type string!");
            }
        }  
        
        if(isset($data['id_item']))
        {
            if(!is_int($data['id_item']))
            {
                throw new JapaModelException("'id_item' isnt from type int!");
            }
        }   
        if(isset($data['type']))
        {
            if(!is_int($data['type']))
            {
                throw new JapaModelException("'type' isnt from type int!");
            }
        } 
        else
        {
            throw new JapaModelException("'type' isnt defined");
        }
        
        return true;
    }
}

?>
