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
 * ** Lock a given node by an node**
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'lock',
 *                      'id_node => (int)node ID to lock,
 *                      'by_id_node' => (int)node ID that locks));
 *
 * Return: 1) TRUE if a node was successfull locked
 *         2) node ID, which locked the node in an other session
 *
 * ** Unlock a given node **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_node => (int)locked node ID )); 
 *
 * 
 * ** Is a node locked ? **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_node => (int)node ID ));  
 *
 * Return: 1) TRUE if a node was locked by the logged node it self
 *         2) FALSE if a node isnt locked
 *         3) node ID, which locked the node in an other session
 *
 * ** Get all access times **
 *
 * ** unlock all locked node ? **
 *
 * $model->action('navigation','lock',
 *                array('job' => (string)'unlock_all'));  
 *
 *
 * ** Remove node locks from a given node that locks **
 *
 * $model->action('navigation','lock',
 *                array('job'    => (string)'unlock_from_node',
 *                      'id_node => (int)node ID that locks));  
 *
 */
class ActionMiscLock extends JapaAction
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
            case 'locktext':
                return $this->lockText($data);
            case 'unlocktext':
                $this->unlockText($data);
                return;
            case 'is_textlocked':
                return $this->isTextLocked($data);
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
        if( isset($data['id_text']) && !is_int($data['id_text']) )
        {
            throw new JapaModelException('"id_text" isnt from type int');         
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
    private function lockText($data)
    {
        $result = $this->isTextLocked($data);
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
            $sql = "INSERT INTO {$this->config->dbTablePrefix}misc_text_lock
                        (`id_text`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_text']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql);
        }
        return TRUE;
    }
    
    /**
     * Unlock a node
     *
     * @param array $data
     */    
    private function unlockText($data)
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}misc_text_lock
                  WHERE
                   `id_text`={$data['id_text']}";

        $this->model->dba->query($sql);       
    }   
    
    /**
     * Delete all locks which are older than 1 hour
     *
     */    
    private function deleteExpiredLocks()
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}misc_text_lock
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
        $sql = "DELETE FROM {$this->config->dbTablePrefix}misc_text_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}misc_text_lock
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
    private function isTextLocked(&$data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config->dbTablePrefix}misc_text_lock
                WHERE
                   `id_text`={$data['id_text']}";

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