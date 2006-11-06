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
 * ActionNavigationRemoveKeyword class 
 *
 * remove key/node relations
 *
 * USAGE:
 *
 * $model->action('navigation','removeKeyword',
 *                array('id_node' => int,
 *                      'id_key'  => int));
 *
 */
 
class ActionNavigationRemoveKeyword extends JapaAction
{
    private $sqlNode = '';
    private $sqlKey  = '';
    
    /**
     * delete key relations
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {         
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}navigation_keyword
                  WHERE
                   {$this->sqlNode}
                   {$this->sqlKey}";

        $this->model->dba->query($sql);   
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(isset($data['id_node']))
        {
            if(!is_int($data['id_node']))
            {
                throw new SmartModelException('"id_node" isnt from type int');        
            }   
            $this->sqlNode = "`id_node`={$data['id_node']}";
            $selcetedItem  = TRUE;
        }    
        
        if(isset($data['id_key'])) 
        {
            if(!is_int($data['id_key']))
            {
                throw new SmartModelException("'id_key' isnt from type int");
            }  
            if(isset($selcetedItem))
            {
                $this->sqlKey = " AND ";
            }
            $this->sqlKey .= "`id_key`={$data['id_key']}";
            $selcetedItem  = TRUE;
        }

        if(!isset($selcetedItem))
        {
            throw new SmartModelException('Whether "id_key" nor "id_node" is defined');                      
        }
         
        return TRUE;
    }
}

?>
