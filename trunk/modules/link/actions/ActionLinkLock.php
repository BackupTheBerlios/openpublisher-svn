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
 * ActionNavigationLock class 
 *
 */

/**
 * USE:
 *
 * ** Lock a given link by an user **
 *
 * $model->action('link','lock',
 *                array('job'    => (string)'lock',
 *                      'id_link => (int)link ID to lock,
 *                      'by_id_link' => (int)link ID that locks));
 *
 * Return: 1) TRUE if a link was successfull locked
 *         2) user ID, which locked the link in an other session
 *
 * ** Unlock a given link **
 *
 * $model->action('link','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_link => (int)locked link ID )); 
 *
 * 
 * ** Is a link locked ? **
 *
 * $model->action('link','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_link => (int)link ID ));  
 *
 * Return: 1) TRUE if a link was locked by the logged user it self
 *         2) FALSE if a link isnt locked
 *         3) user ID, which locked the link in an other session
 *
 * ** Get all access times **
 *
 * ** unlock all locked link ? **
 *
 * $model->action('link','lock',
 *                array('job' => (string)'unlock_all'));  
 *
 *
 * ** Remove link locks from a given user that locks **
 *
 * $model->action('link','lock',
 *                array('job'    => (string)'unlock_from_link',
 *                      'id_link => (int)link ID that locks));  
 *
 */
class ActionLinkLock extends JapaAction
{
    /**
     * User lock actions
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        $this->deleteExpiredLocks();
        
        switch($data['job'])
        {
            case 'lock':
                return $this->lockLink($data);
            case 'unlock':
                $this->unlockLink($data);
                return;
            case 'is_locked':
                return $this->isLinkLocked($data);
            case 'unlock_all':
                $this->unlockAll(); 
                return;
            case 'unlock_from_user':
                $this->unlockByIdUser($data); 
                return;                
            default:
                throw new JapaModelException('Action not available: '.$data['job']); 
        }
        
        return TRUE;
    }
    
    /**
     * validate data
     *
     * @param array $data 
     * @return bool 
     */    
    public function validate( $data = FALSE )
    {
        if(!isset($data['job']))
        {
            throw new JapaModelException('"job" isnt set');
        }
        elseif(!is_string($data['job']))
        {
            throw new JapaModelException('"job" isnt from type string');
        } 
        
        if( isset($data['id_link']) && !is_int($data['id_link']) )
        {
            throw new JapaModelException('"id_link" isnt from type int');         
        }    
        if( isset($data['by_id_user']) && !is_int($data['by_id_user']) )
        {
            throw new JapaModelException('"by_id_user" isnt from type int');         
        }         
        return TRUE;
    }

    /**
     * Lock a node for modifying
     *
     * @param array $data Node data
     */    
    private function lockLink($data)
    {
        $result = $this->isLinkLocked($data);
        // False = the node isnt locked
        // True = the node is locked by the logged user
        // if not locked by the logged user, $result
        // contains the id of the user which locks
        if(($result !== FALSE) && ($result !== TRUE))
        {
            return $result;
        }
        
        if($result == FALSE)
        {
            $sql = "REPLACE INTO {$this->config->dbTablePrefix}link_lock
                        (`id_link`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_link']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql); 
        }
        return TRUE;
    }
    
    /**
     * Unlock a node
     *
     * @param array $data
     */    
    private function unlockLink($data)
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}link_lock
                  WHERE
                   `id_link`={$data['id_link']}";

        $this->model->dba->query($sql);       
    }   
    
    /**
     * Delete all locks which are older than 1 hour
     *
     */    
    private function deleteExpiredLocks()
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}link_lock
                  WHERE
                   `lock_time` < NOW()-3600";

        $this->model->dba->query($sql);         
    }  
    
    /**
     * Unlock all entries
     *
     */    
    private function unlockAll()
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}link_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}link_lock
                WHERE `by_id_user`={$data['id_user']}";

        $this->model->dba->query($sql);        
    }
    
    /**
     * Check if a node is locked and if yes by which id_user
     *
     * @param array $data User data
     * @param mixed FALSE if not locked True if locked by the logged user
     *              else id_user of the user who locks
     */    
    private function isLinkLocked($data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config->dbTablePrefix}link_lock
                WHERE
                   `id_link`={$data['id_link']}";

        $result = $this->model->dba->query($sql); 
       
        if($result->numRows() == 1)
        {            
            $row = $result->fetchAssoc();
            
            if($data['by_id_user'] == $row['by_id_user'])
            {
                return TRUE;
            }

            return $row['by_id_user'];
        }
        
        return FALSE;
    }     
}

?>