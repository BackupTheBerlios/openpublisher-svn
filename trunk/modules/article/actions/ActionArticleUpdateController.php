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
 * ActionNavigationUpdateNode class 
 *
 * USAGE:
 */

 
class ActionArticleUpdateView extends JapaAction
{
    /**
     * update navigation node
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_view_rel
                   (`id_article`,`id_view`)
                VALUES
                   ({$data['id_article']},{$data['id_view']})";

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
        if(!isset($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt defined');        
        }    
        if(!is_int($data['id_article']))
        {
            throw new SmartModelException('"id_article" isnt from type int');        
        }
        if(!isset($data['id_view']))
        {
            throw new SmartModelException('"id_view" isnt defined');        
        }    
        if(!is_int($data['id_view']))
        {
            throw new SmartModelException('"id_view" isnt from type int');        
        }       
        return TRUE;
    }
}

?>
