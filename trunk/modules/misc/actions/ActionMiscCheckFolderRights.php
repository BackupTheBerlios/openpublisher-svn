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
 * ActionMiscCheckFolderRights 
 *
 * USAGE:
 *
 * $model->action('misc','checkFolderRights', array('error' => & array() ));
 *
 */
 
class ActionMiscCheckFolderRights extends JapaAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $data_folder = JAPA_BASE_DIR . 'data/misc';
        if(!is_writeable($data_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$data_folder;    
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