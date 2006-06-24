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
 * ActionArticleDeleteArticle class 
 *
 * USAGE:
 *
 * $model->action('article','deleteArticle',
 *                array('id_article'  => int))
 *
 */
 
class ActionArticleDeleteUser extends SmartAction
{
    /**
     * delete article and navigation node relation
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {                 
        // remove article user relations
        $this->model->action('article','removeUser',
                             array('id_user' => & $data['id_user']) );        
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {         
        if(!isset($data['id_user']))
        {
            throw new SmartModelException('"id_user" isnt defined');        
        }    
        elseif(!is_array($data['id_user']) && !is_int($data['id_user']))
        {
            throw new SmartModelException('"id_user" isnt from type int or array');        
        }
        
        return TRUE;
    }
}

?>
