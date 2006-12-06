<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ControllerLogout class
 *
 */

class ControllerLogout extends JapaControllerAbstractPage
{
    /**
     * dont render a view
     */
    public $renderView = false;
    
    /**
     * Execute the logout view
     */
    public function perform()
    {
        // Check if the visitor is a logged user
        //
        if(null !== ($loggedUserId = $this->model->session->get('loggedUserId')))
        {     
            // send a broadcast logout message to all modules
            $this->model->broadcast('logout', array('loggedUserId' => (int)$loggedUserId));  
        
        }
        // reload the public web controller
        $this->router->redirect();
    }
}

?>