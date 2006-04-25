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
 * ActionNavigationIsSubNode class 
 *
 * USAGE:
 *
 * $this->model->action('navigation','isSubNode',
 *                      array('id_node1' => int,
 *                            'id_node2' => int));
 *
 * return bool
 *
 */
 
class ActionNavigationIsSubNode extends SmartAction
{
    /**
     * check if node $data['id_node1'] is a subnode of node $data['id_node2']
     *
     * @param array $data
     * @return bool true or false
     */
    function perform( $data = FALSE )
    { 
        $this->tree = array();
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => $data['id_node2'],
                                   'result'  => & $this->tree,
                                   'fields'  => array('id_parent','status','id_node')));   
        
        return $this->isSubNode( $data['id_node1'] );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true else throw an exception
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node1']))
        {
            throw new SmartModelException('Action data var "id_node1" isnt defined');        
        }
        
        if(!isset($data['id_node2']))
        {
            throw new SmartModelException('Action data var "id_node2" isnt defined');        
        }   
        
        if(!is_int($data['id_node2']))
        {
            throw new SmartModelException('"id_node2" isnt from type int');        
        }    
        
        if(!is_int($data['id_node1']))
        {
            throw new SmartModelException('"id_node1" isnt from type int');        
        }   
        
        return TRUE;
    }
    /**
     * check if $id_node is a subnode
     *
     * @param int $id_node
     * @return bool true or false
     */    
    private function isSubNode( $id_node )
    { 
    
        foreach($this->tree as $node)
        {
            if($node['id_node'] == (int)$id_node)
            {
                return TRUE;
            }
        }
        return FALSE;
    }    
}

?>
