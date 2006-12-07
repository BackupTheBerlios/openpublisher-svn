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
 * ViewUserMain class
 *
 */

class ControllerUserMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
        
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        // init users template variable 
        $this->viewVar['users'] = array();
        
        // assign template variable with users
        $this->model->action('user', 'getUsers',
                             array('result'         => & $this->viewVar['users'],
                                   'translate_role' => TRUE,
                                   'or_id_user'     => (int)$this->controllerVar['loggedUserId'],
                                   'role'           => array('>',$this->config->getVar('loggedUserRole')),
                                   'fields' => array('id_user','status',
                                                     'login','role',
                                                     'name','lastname')));  
        
        // get user locks
        $this->getLocks();
        
        // set template variable that show the link to add users
        // only if the logged user have at least administrator rights
        if($this->controllerVar['loggedUserRole'] <= 20)
        {
            $this->viewVar['showAddUserLink'] = TRUE;
        }
        else
        {
            $this->viewVar['showAddUserLink'] = FALSE;
        }
    }  
     /**
     * assign template variables with lock status of each user
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['users'] as $user)
        {
            // lock the user to edit
            $result = $this->model->action('user','lock',
                                     array('job'        => 'is_locked',
                                           'id_user'    => (int)$user['id_user'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['users'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['users'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }
}

?>