<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2005
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ActionNavigationUpdateSubNodes class 
 * Update data of subnodes
 *
 * USAGE:
 *
 * $this->model->action('navigation','updateSubNodes',
 *                      array('id_node' => int,
 *                            'fields'  => array('status'    => int,
 *                                               'id_sector' => int)));
 *
 *
 */
 
class ActionNavigationUpdateSubNodes extends JapaAction
{
    /**
     * allowed fields
     */
    protected $tblFields_node = array('id_sector' => 'Int',
                                      'status'    => 'Int',
                                      'id_view'   => 'Int');
    /**
     * update data of subnodes
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    { 
        $tree = array();
        // get subnode of a given node
        $this->model->action('navigation','getTree', 
                             array('id_node' => $data['id_node'],
                                   'result'  => & $tree,
                                   'fields'  => array('id_parent','status','id_node')));   
        if( count($tree) > 0 )
        {
            // update subnodes
            foreach($tree as $node)
            {
                $this->model->action('navigation','updateNode', 
                                     array('id_node' => (int)$node['id_node'],
                                           'fields'  => $data['fields'] ));              
            }
        }
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true else throw an exception
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('Action data var "id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('Action data var "id_node" isnt from type int');        
        }        
        
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        // check if database fields exists
        foreach($data['fields'] as $key => $val)
        {
            if(!isset($this->tblFields_node[$key]))
            {
                throw new JapaModelException("Field '".$key."' isnt allowed to update!");
            }
        }
        
        return TRUE;
    }  
}

?>
