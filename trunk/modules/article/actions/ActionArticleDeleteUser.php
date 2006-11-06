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
 * ActionArticleDeleteUser class 
 *
 * Delete article user relations when an user or users were deleted
 *
 * USAGE:
 *
 * $model->action('article','deleteUser',
 *                array('id_user'  => int or array))
 *
 */
 
class ActionArticleDeleteUser extends JapaAction
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
