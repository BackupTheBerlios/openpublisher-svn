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
 * ActionLinkDeleteNodeRelatedContent class 
 *
 * USAGE:
 *
 * $model->action('link','linkDeleteNodeRelatedContent',
 *                array('id_node' => int))
 *
 *
 * DEPENDENCIES:
 * - $this->model->action('link','deleteLink');
 *
 */
 
class ActionLinkDeleteNodeRelatedContent extends JapaAction
{
    /**
     * delete navigation node related links
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {  
        $sql = "DELETE FROM {$this->config->dbTablePrefix}link_links
                  WHERE
                   `id_node`={$data['id_node']}";

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
        elseif(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
        }
               
        return TRUE;
    }
}

?>
