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
 * ViewUserLogout class
 *
 */

class ViewUserAdminLogout extends SmartView
{    
    /**
     * Destroy current session and reload the admin controller
     *
     */
    public function perform()
    {
        // free locks from this user
        $this->model->broadcast('lock',array('job'     => 'unlock_from_user',
                                             'id_user' => (int)$this->viewVar['loggedUserId']));
        
        $this->model->session->destroy();
        ob_clean();
        @header('Location: ' . $this->config['admin_web_controller']);
        exit;        
    }  
}

?>