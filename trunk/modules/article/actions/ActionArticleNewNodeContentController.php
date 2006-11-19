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
 * ActionArticleNewNodeContentView class 
 *
 * Update article view of a new node (id_node) with the article 
 * view of the parent node (id_parent)
 *
 * USAGE:
 * $model->action( 'article', 'newNodeContentView',
 *                 array('id_node'   => int,
 *                       'id_parent' => int ));
 *
 *
 */
 
class ActionArticleNewNodeContentView extends JapaAction
{
    /**
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {       
        // get associated view of the parent node
        $tmp = array();
        // get current node data
        $this->model->action('article','GetNodeAssociatedView', 
                             array('result'  => & $tmp,
                                   'id_node' => (int)$data['id_parent'])); 

        // if no id_view defined return                                   
        if(!isset($tmp['id_view']))
        {
            return;
        }

        // update article node view
        $this->model->action('article','updateNodeView',
                             array('id_node' => (int)$data['id_node'], 
                                   'id_view' => (int)$tmp['id_view']) );
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt defined');        
        }    
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }
        if(!isset($data['id_parent']))
        {
            throw new JapaModelException('"id_parent" isnt defined');        
        }    
        if(!is_int($data['id_parent']))
        {
            throw new JapaModelException('"id_parent" isnt from type int');        
        }        
        
        if($data['id_parent'] == 0)
        {
            return FALSE;
        }
        return TRUE;
    }
}

?>
