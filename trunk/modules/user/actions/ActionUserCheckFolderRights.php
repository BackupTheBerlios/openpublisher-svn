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
 * ActionUserCheckFolderRights
 *
 * USAGE:
 *
 * $model->action('user','checkFolderRights', array('error' => & array() )); 
 */
 
class ActionUserCheckFolderRights extends JapaAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $user_folder = JAPA_BASE_DIR . 'data/user';
        if(!is_writeable($user_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$user_folder;    
        }      

        return TRUE;
    } 
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt defined");
        }
        if(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }
        
        return TRUE;
    }
}

?>