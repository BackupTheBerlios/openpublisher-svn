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
 * ControllerUserAdminLogout class
 *
 */

class ControllerUserAdminLogout extends JapaControllerAbstractPage
{    
    /**
     * Destroy current session and reload the admin controller
     *
     */
    public function perform()
    {
        // free locks from this user
        $this->model->broadcast('lock',array('job'     => 'unlock_from_user',
                                             'id_user' => (int)$this->controllerVar['loggedUserId']));
        
        $this->model->session->destroy();
        ob_clean();
        @header('Location: ' . $this->controllerVar['url_base']);
        exit;        
    }  
}

?>