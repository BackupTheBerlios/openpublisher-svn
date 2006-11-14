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
 * ControllerUserAddUser class
 *
 */
 
class ControllerUserAddUser extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
        
    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to execute this view
        if(FALSE == $this->checkViewPermission())
        {
            throw new JapaViewException('Operation denied');
        }    
    }
    
    /**
     * Execute the view of the template "tpl.adduser.php"
     *
     * @return bool true on success else false
     */
    function perform()
    { 
        // Init template form field values
        $this->viewVar['error']            = array();
        $this->viewVar['form_email']       = '';
        $this->viewVar['form_status']      = 0;
        $this->viewVar['form_login']       = '';
        $this->viewVar['form_passwd']      = '';
        $this->viewVar['form_name']        = '';
        $this->viewVar['form_lastname']    = '';  
        $this->viewVar['form_website']     = '';
        $this->viewVar['form_description'] = '';   
        $this->viewVar['role']             = 0;  

        // add user on demande
        if( false !== $this->httpRequest->getParameter( 'addthisuser', 'post', 'alpha' ) )
        {
            $form_role = $this->httpRequest->getParameter( 'role', 'post', 'int' );

            if(FALSE == $this->checkAssignedPermission( (int) $form_role ))
            {
                $this->resetFormData();
                $this->viewVar['error'][] = 'You have no rights to assign the such role to a new user!';
                $this->assignHtmlSelectBoxRole();
                return TRUE;
            }
            
            // get form data
            $user_data = $this->getFormData();          

            // array with new user data
            $_data = array( 'error' => & $this->viewVar['error'],
                            'user'  => $user_data );
             
            // add new user data
            if(false !== ($id_user = $this->model->action( 'user','add',$_data )))
            {
                // reload the user module on success
                @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/user/view/editUser/id_user='.$id_user);
                exit; 
            }
            else
            {
                // reset form fields on error
                $this->resetFormData();
                $this->assignHtmlSelectBoxRole();
                return TRUE;                
            }
        }

        $this->assignHtmlSelectBoxRole();
        
        return TRUE;
    } 

    /**
     * Assign template variable to build the html role select box
     */
    private function assignHtmlSelectBoxRole()
    {
        // build template variables for the user role html select menu
        $roles = array('10'  => 'Superuser',
                       '20'  => 'Administrator',
                       '40'  => 'Editor',
                       '60'  => 'Author',
                       '100' => 'Webuser'); 
        
        $this->viewVar['form_roles'] = array();
        
        foreach($roles as $key => $val)
        {
            // just the roles on which the logged user has rights
            if(($this->controllerVar['loggedUserRole'] < $key) && ($this->controllerVar['loggedUserRole'] <= 40))
            {
                $this->viewVar['form_roles'][$key] = $val;
            }
        }
    }

    /**
     * A logged user can only create new users with a role
     * value greater than the value of its own role.
     *
     * @param int $assignedRole Role of the new user
     */
    private function checkAssignedPermission( $assignedRole )
    {
        if($this->controllerVar['loggedUserRole'] >= (int)$assignedRole)
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->controllerVar['loggedUserRole'] <= 40)
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    private function getFormData()
    {
        $form = array();
         
        $form['login']    = (string)$this->httpRequest->getParameter( 'login', 'post', 'alnum' );
        $form['name']     = (string)$this->httpRequest->getParameter( 'name', 'post', 'alnum' );
        $form['lastname'] = (string)$this->httpRequest->getParameter( 'lastname', 'post', 'alnum' );
        $form['passwd']   = (string)$this->httpRequest->getParameter( 'passwd', 'post', 'alnum' );
        $form['email']    = (string)$this->httpRequest->getParameter( 'email', 'post', 'raw' );
        $form['status']   = (int)$this->httpRequest->getParameter( 'status', 'post', 'int' );
        $form['role']     = (int)$this->httpRequest->getParameter( 'role', 'post', 'int' );
           
        return $form;
    }  
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->viewVar['role']          = $this->httpRequest->getParameter( 'role', 'post', 'int' );
        $this->viewVar['form_status']   = $this->httpRequest->getParameter( 'status', 'post', 'int' );
        $this->viewVar['form_email']    = (string)$this->httpRequest->getParameter( 'email', 'post', 'raw' );
        $this->viewVar['form_name']     = (string)$this->httpRequest->getParameter( 'name', 'post', 'alnum' );
        $this->viewVar['form_lastname'] = (string)$this->httpRequest->getParameter( 'lastname', 'post', 'alnum' );
        $this->viewVar['form_login']    = (string)$this->httpRequest->getParameter( 'login', 'post', 'alnum' );
        $this->viewVar['form_passwd']   = (string)$this->httpRequest->getParameter( 'passwd', 'post', 'alnum' );         
    }       
}

?>