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
 * ActionLinkDeleteLink class 
 *
 * USAGE:
 *
 * $model->action('link','deleteLink',
 *                array('id_link'  => int))
 *
 */
 
class ActionLinkDeleteLink extends JapaAction
{
    /**
     * delete link and relations
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_links
                  WHERE
                   `id_link`={$data['id_link']}";

        $this->model->dba->query($sql);
        
        $sql = "DELETE FROM {$this->config['dbTablePrefix']}link_keyword
                  WHERE
                   `id_link`={$data['id_link']}";

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
        if(!isset($data['id_link']))
        {
            throw new JapaModelException('"id_link" isnt defined');        
        }    
        elseif(!is_int($data['id_link']))
        {
            throw new JapaModelException('"id_link" isnt from type int');        
        }
        
        return TRUE;
    }
}

?>
