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
 * ActionUserLogAddSession class 
 * 
 * USAGE:
 * 
 * $model->action('user','logAddSession',
 *                array('id_session' => & int,
 *                      'user'  => array('id_user' => int,
 *                                       'ip'      => string,
 *                                       'host'    => string,
 *                                       'agent'   => string)))
 *
 */

 
class ActionUserLogAddSession extends SmartAction
{
    private $tblFields = array('id_user' => 'Int',
                               'ip'      => 'String',
                               'host'    => 'String',
                               'agent'   => 'String');
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    {        
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['user'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }    
        
        $sql = "INSERT INTO {$this->config['dbTablePrefix']}user_log_session
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);
        
        $data['id_session'] = $this->model->dba->lastInsertID();
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
        foreach($data['user'] as $key => $val)
        {
            if(!isset($this->tblFields[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(isset($data['id_session']))
        {
            if(!is_int($data['id_session']))
            {
                throw new SmartModelException("'id_session' isnt from type int!");
            }
        }
        else
        {
            throw new SmartModelException("'id_session' isnt defined");
        }
        
        if(isset($data['user']['id_user']))
        {
            if(!is_int($data['user']['id_user']))
            {
                throw new SmartModelException("'id_user' isnt from type int!");
            }
        }
        else
        {
            throw new SmartModelException("'id_user' isnt defined");
        }
        
        if(isset($data['user']['ip']))
        {
            if(!is_string($data['user']['ip']))
            {
                throw new SmartModelException("'ip' isnt from type string!");
            }
        }        
        if(isset($data['user']['host']))
        {
            if(!is_string($data['user']['host']))
            {
                throw new SmartModelException("'host' isnt from type string!");
            }
        }   
        if(isset($data['user']['agent']))
        {
            if(!is_string($data['user']['agent']))
            {
                throw new SmartModelException("'agent' isnt from type string!");
            }
        }  
        
        return TRUE;
    }
}

?>
