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
 * ActionArticleLock class 
 *
 */

/**
 * USE:
 *
 * ** Lock a given Article by an user **
 *
 * $model->action('article','lock',
 *                array('job'    => (string)'lock',
 *                      'id_article => (int)Article ID to lock,
 *                      'by_id_article' => (int)Article ID that locks));
 *
 * Return: 1) TRUE if a Article was successfull locked
 *         2) user ID, which locked the Article in an other session
 *
 * ** Unlock a given Article **
 *
 * $model->action('article','lock',
 *                array('job'    => (string)'unlock',
 *                      'id_article => (int)locked Article ID )); 
 *
 * 
 * ** Is a Article locked ? **
 *
 * $model->action('article','lock',
 *                array('job'    => (string)'is_locked',
 *                      'id_article => (int)Article ID ));  
 *
 * Return: 1) TRUE if a Article was locked by the logged user it self
 *         2) FALSE if a Article isnt locked
 *         3) user ID, which locked the Article in an other session
 *
 * ** Get all access times **
 *
 * ** unlock all locked Article ? **
 *
 * $model->action('article','lock',
 *                array('job' => (string)'unlock_all'));  
 *
 *
 * ** Remove Article locks from a given user that locks **
 *
 * $model->action('article','lock',
 *                array('job'    => (string)'unlock_from_Article',
 *                      'id_article => (int)Article ID that locks));  
 *
 */
class ActionArticleLock extends JapaAction
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
                return $this->lockArticle($data);
            case 'unlock':
                $this->unlockArticle($data);
                return;
            case 'is_locked':
                return $this->isArticleLocked($data);
            case 'unlock_all':
                $this->unlockAll(); 
                return;
            case 'unlock_from_user':
                $this->unlockByIdUser($data); 
                return;                
            default:
                throw new SmartModelException('Action not available: '.$data['job']); 
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
            throw new SmartModelException('"job" isnt set');
        }
        elseif(!is_string($data['job']))
        {
            throw new SmartModelException('"job" isnt from type string');
        } 
        
        if( isset($data['id_article']) && !is_int($data['id_article']) )
        {
            throw new SmartModelException('"id_article" isnt from type int');         
        }    
        if( isset($data['by_id_user']) && !is_int($data['by_id_user']) )
        {
            throw new SmartModelException('"by_id_user" isnt from type int');         
        }         
        return TRUE;
    }

    /**
     * Lock a node for modifying
     *
     * @param array $data Node data
     */    
    private function lockArticle($data)
    {
        $result = $this->isArticleLocked($data);
        
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
            $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_lock
                        (`id_article`,`lock_time`,`by_id_user`)
                    VALUES
                       ({$data['id_article']},NOW(),{$data['by_id_user']})";

            $this->model->dba->query($sql); 
        }
        return TRUE;
    }
    
    /**
     * Unlock a node
     *
     * @param array $data
     */    
    private function unlockArticle($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock
                  WHERE
                   `id_article`={$data['id_article']}";

        $this->model->dba->query($sql);       
    }   
    
    /**
     * Delete all locks which are older than 5 hour
     *
     */    
    private function deleteExpiredLocks()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock
                  WHERE
                   `lock_time` < (NOW()-{$this->config['max_lock_time']})";

        $this->model->dba->query($sql);         
    }  
    
    /**
     * Unlock all entries
     *
     */    
    private function unlockAll()
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock";

        $this->model->dba->query($sql);        
    }      

    /**
     * Unlock all entries which a user has locked
     *
     */    
    private function unlockByIdUser($data)
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}article_lock
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
    private function isArticleLocked($data)
    {
        $sql = "SELECT 
                    `by_id_user` 
                FROM 
                    {$this->config['dbTablePrefix']}article_lock
                WHERE
                   `id_article`={$data['id_article']}";

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