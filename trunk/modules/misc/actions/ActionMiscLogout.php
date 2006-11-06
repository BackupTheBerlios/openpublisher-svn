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
 * ActionMiscLogout class 
 *
 * USAGE:
 *
 * $model->action('misc','logout')
 * 
 */
 
class ActionMiscLogout extends JapaAction
{
    /**
     * process logout 
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        // free locks from this user
        $this->model->action('misc','lock',
                             array('job'     => 'unlock_from_user',
                                   'id_user' => (int)$data['loggedUserId']));
    }
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate(  $data = FALSE  )
    {
        if( !isset($data['loggedUserId']) || !is_int($data['loggedUserId']) )
        {        
            trigger_error('"loggedUserId" must be defined and from type int', E_USER_ERROR); 
            return FALSE;
        }
    
        return TRUE;
    }    
}

?>
