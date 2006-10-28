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
 * ViewUserAddUser class
 *
 */
 
class ViewUserAddUser extends JapaControllerAbstractPage
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'adduser';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/user/templates/';

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to execute this view
        if(FALSE == $this->checkViewPermission())
        {
            throw new SmartViewException('Operation denied');
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
        $this->tplVar['error']            = array();
        $this->tplVar['form_email']       = '';
        $this->tplVar['form_status']      = 0;
        $this->tplVar['form_login']       = '';
        $this->tplVar['form_passwd']      = '';
        $this->tplVar['form_name']        = '';
        $this->tplVar['form_lastname']    = '';  
        $this->tplVar['form_website']     = '';
        $this->tplVar['form_description'] = '';   
        $this->tplVar['role']             = 0;  
    
        // add user on demande
        if( isset($_POST['addthisuser']) )
        {
            if(FALSE == $this->checkAssignedPermission( (int)$_POST['role'] ))
            {
                $this->resetFormData();
                $this->tplVar['error'][] = 'You have no rights to assign the such role to a new user!';
                $this->assignHtmlSelectBoxRole();
                return TRUE;
            }
            
            // check if required fields are empty
            if (FALSE == $this->checkEmptyFields())
            {
                // reset form fields on error
                $this->resetFormData();
                $this->tplVar['error'][] = 'You have fill out the login, name, lastname, email and password fields!';
                $this->assignHtmlSelectBoxRole();
                return TRUE;
            }            

            // array with new user data
            $_data = array( 'error'     => & $this->tplVar['error'],
                            'user' => array('email'    => SmartCommonUtil::stripSlashes((string)$_POST['email']),
                                            'status'   => (int)$_POST['status'],
                                            'role'     => (int)$_POST['role'],
                                            'login'    => SmartCommonUtil::stripSlashes((string)$_POST['login']),
                                            'name'     => SmartCommonUtil::stripSlashes((string)$_POST['name']),
                                            'lastname' => SmartCommonUtil::stripSlashes((string)$_POST['lastname']),
                                            'passwd'   => SmartCommonUtil::stripSlashes((string)$_POST['passwd']) ));
             
            // add new user data
            if(FALSE !== ($id_user = $this->model->action( 'user','add',$_data )))
            {
                // reload the user module on success
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=user&view=editUser&id_user='.$id_user);
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
        
        $this->tplVar['form_roles'] = array();
        
        foreach($roles as $key => $val)
        {
            // just the roles on which the logged user has rights
            if(($this->viewVar['loggedUserRole'] < $key) && ($this->viewVar['loggedUserRole'] <= 40))
            {
                $this->tplVar['form_roles'][$key] = $val;
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
        if($this->viewVar['loggedUserRole'] >= (int)$assignedRole)
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
        if($this->viewVar['loggedUserRole'] <= 40)
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
    private function checkEmptyFields()
    {
        // check if some fields are empty
        if( empty($_POST['login']) || 
            empty($_POST['email']) || 
            empty($_POST['lastname']) || 
            empty($_POST['name']) || 
            empty($_POST['passwd']) )
        {        
            return FALSE;
        }  
        return TRUE;
    }  
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->tplVar['role']          = SmartCommonUtil::stripSlashes($_POST['role']);
        $this->tplVar['form_status']   = $_POST['status'];
        $this->tplVar['form_email']    = SmartCommonUtil::stripSlashes($_POST['email']);
        $this->tplVar['form_name']     = SmartCommonUtil::stripSlashes($_POST['name']);
        $this->tplVar['form_lastname'] = SmartCommonUtil::stripSlashes($_POST['lastname']);
        $this->tplVar['form_login']    = SmartCommonUtil::stripSlashes($_POST['login']);
        $this->tplVar['form_passwd']   = SmartCommonUtil::stripSlashes($_POST['passwd']);          
    }       
}

?>