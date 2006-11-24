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
 * ActionArticleUpdateNodeController class 
 *
 * USAGE:
 * $model->action('article','updateNodeController',
 *                array('id_node' => int,
 *                      'id_controller' => int))
 */

 
class ActionArticleUpdateNodeController extends JapaAction
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_node_controller_rel
                   (`id_node`,`id_controller`)
                VALUES
                   ({$data['id_node']},{$data['id_controller']})";

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
        if(!isset($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt defined');        
        }    
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }
        if(!isset($data['id_controller']))
        {
            throw new JapaModelException('"id_controller" isnt defined');        
        }    
        if(!is_int($data['id_controller']))
        {
            throw new JapaModelException('"id_controller" isnt from type int');        
        }        
        return TRUE;
    }
}

?>
