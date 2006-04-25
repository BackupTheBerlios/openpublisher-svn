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
 * ActionKeywordDelete class 
 *
 * USAGE:
 *
 * $model->action('keyword','delete',
 *                array('id_key'  => int))
 *
 */
 
class ActionKeywordDelete extends SmartAction
{
    /**
     * delete keyword and subkeywords
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {        
        $this->deleteSubKeywords( $data['id_key'] );
        $this->deleteKeyword( $data['id_key'] );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_key']))
        {
            throw new SmartModelException('"id_key" isnt defined');        
        }    
        elseif(!is_int($data['id_key']))
        {
            throw new SmartModelException('"id_key" isnt from type int');        
        }

        return TRUE;
    }
    /**
     * delete single keyword and send a broadcast action
     * to other modules
     *
     * @param int $id_key
     */    
    private function deleteKeyword( $id_key )
    {
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword_lock
                  WHERE
                   `id_key`={$id_key}";

        $this->model->dba->query($sql);
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}keyword
                  WHERE
                   `id_key`={$id_key}";

        $this->model->dba->query($sql);
        
        // delete all keyword relations from other modules
        $this->model->broadcast('removeKeyword', array('id_key' => (int)$id_key)); 
    }
    /**
     * delete subkeyword of a given id_key
     *
     * @param int $id_key
     */      
    private function deleteSubKeywords( $id_key )
    {
        $tree = array();
        
        // get sub keywords
        $this->model->action('keyword','getTree', 
                             array('id_key'   => (int)$id_key,
                                   'result'    => & $tree,
                                   'fields'    => array('id_parent','status','id_key')));   
   
        if(count($tree) > 0)
        {
            foreach($tree as $key)
            {
                $this->deleteKeyword( $key['id_key'] );
            }
        }
    }
}

?>
