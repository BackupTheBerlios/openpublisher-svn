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
 * ActionUserLogout class 
 *
 * USAGE:
 *
 * $model->action('user','logout')
 * 
 */
 
class ActionUserLogout extends SmartAction
{
    /**
     * process logout 
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    { 
        // free locks from this user
        $this->model->action('user','lock',
                             array('job'     => 'unlock_from_user',
                                   'id_user' => (int)$data['loggedUserId']));
        
        $this->model->session->destroy();
        ob_clean();
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
            throw new SmartModelException ('"loggedUserId" must be defined and from type int'); 
        }
    
        return TRUE;
    }    
}

?>
