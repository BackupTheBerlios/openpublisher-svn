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
 * ActionUserAccess class 
 *
 */

/**
 * USE:
 *
 * ** Update/Insert time of a given user **
 * $model->action('user','access',
 *                array('job'    => string,          // value: 'update'
 *                      'id_user => int ));
 *
 * ** Get last access time of a given user **
 *
 * $t = $model->action('user','access',
 *                     array('job'    => string,  // value: 'last'
 *                           'id_user => int )); 
 *
 * Return: 1. FALSE if no user 
 *         2. Time (0000-00-00 00:00:00)
 * 
 * ** Get last x access times **
 *
 * $t = $model->action('user','access',
 *                     array('job' => string,     // value: 'last_x'
 *                           'num' => int));      // number of last accesses
 *
 * Return: 1. array[]['access']
 *                   ['id_user']
 *
 * ** Get all access times **
 *
 * $t = $model->action('user','access',
 *                     array('job' => string));  // value: 'all'
 *
 * Return: 1. array[]['access']
 *                   ['id_user']
 *
 * ** Remove a user from the last access list **
 *
 * $model->action('user','access',
 *                array('job'     => string,    // value: 'delete'
 *                      'id_user' => int));  
 *
 *
 */
class ActionUserAccess extends JapaAction
{
    /**
     * User time access jobs
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {   
        switch($data['job'])
        {
            case 'update':
                $this->updateAccess($data);
                return;        
            case 'last':
                return $this->lastAccess($data);
            case 'last_x':
                return $this->lastXAccesses($data);
            case 'all':
                return $this->allAccesses($data);   
            case 'delete':
                $this->deleteAccess($data);   
                return;                
            default:
                throw new SmartModelException('Action not available: '.$data['action']); 
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {       
        if(!isset($data['job']) || !is_string($data['job']) || empty($data['job']))
        {
            throw new SmartModelException('No [job] defined for this action'); 
        }
        
        switch($data['job'])
        {
            case 'update':
                // id_user must exists to update user last access
                if(!isset($data['id_user']))
                {
                    return FALSE;         
                } 
                elseif(!is_int($data['id_user']))
                {
                    throw new SmartModelException('"id_user" action array value type is not int'); 
                }
                break;  
            case 'last':
            case 'delete':
                if(!isset($data['id_user']))
                {
                    throw new SmartModelException('[id_user] isnt set');         
                } 
                elseif(!is_int($data['id_user']))
                {
                    throw new SmartModelException('"id_user" action array value type is not int'); 
                }
                break;
            case 'last_x':
                if( !isset($data['num']) || !is_int($data['num']) )
                {
                    throw new SmartModelException('"num" action array value isnt set or isnt type int');       
                } 
                break;                
            default:
                throw new SmartModelException('[job] not available: '.$data['job']); 
        }        
               
        return TRUE;
    }
    

    /**
     * Update user access time
     *
     * @param array $data User data
     */    
    private function updateAccess($data)
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}user_access
                    SET
                       `id_user`={$data['id_user']},
                       `access`=NOW()";

        $this->model->dba->query($sql); 

        return TRUE;
    }
    
    /**
     * Get last access time of a given id_user
     *
     * @param array $data User data
     * @return array
     */    
    private function lastXAccesses($data)
    {
        $sql = "SELECT 
                    `access`,
                    `id_user`
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                LIMIT
                    {$data['num']}
                ORDER BY
                    `access` DESC";

        $stmt = $this->model->dba->query($sql);  
       
        $res = array();
       
        while($row = $stmt->fetchAssoc())
        {
            $res[] =  array('access'  => $row['access'],
                            'id_user' => $row['id_user']);
        }
        
        return $res;
    }      
    /**
     * Get all accesses
     *
     * @param array $data User data
     * @return array 
     */    
    private function allAccesses($data)
    {
        $sql = "SELECT 
                    `access`,
                    `id_user`
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                ORDER BY
                    `access` DESC";

        $stmt = $this->model->dba->query($sql);  
       
        $res = array();

        while($row = $stmt->fetchAssoc())
        {
            $res[] =  array('access'  => $row['access'],
                            'id_user' => $row['id_user']);
        }
        
        return $res;
    }  
    /**
     * Delete access time of a given id_user
     *
     */    
    private function deleteAccess($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}user_access
                WHERE `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);         
    }
    
    /**
     * Get last access time of a given id_user
     *
     * @param array $data User data
     * @param mixed FALSE if no last access time
     *              else time of last access
     */    
    private function lastAccess($data)
    {
        $sql = "SELECT 
                    `access` 
                FROM 
                    {$this->config['dbTablePrefix']}user_access
                WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->dba->query($sql);  
       
        if($stmt->numRows() == 1)
        {            
            $row = $stmt->fetchAssoc();
            
            return $row['access'];
        }
        
        return FALSE;
    }     
}

?>