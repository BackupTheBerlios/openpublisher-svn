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
 * ActionArticleUpdateController class 
 *
 * USAGE:
 */

 
class ActionArticleUpdateController extends JapaAction
{
    /**
     * update article controller
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        $sql = "REPLACE INTO {$this->config['dbTablePrefix']}article_controller_rel
                   (`id_article`,`id_controller`)
                VALUES
                   ({$data['id_article']},{$data['id_controller']})";

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
            throw new JapaModelException('"id_article" isnt defined');        
        }    
        if(!is_int($data['id_article']))
        {
            throw new JapaModelException('"id_article" isnt from type int');        
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
