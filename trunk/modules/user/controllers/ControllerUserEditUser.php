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
 * ViewUserEditUser class
 *
 */
 
class ViewUserEditUser extends JapaControllerAbstractPage
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'edituser';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/user/templates/';

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // check permission to edit/update requested user data
        if(FALSE == $this->model->action('user','allowEditUser',
                                         array('id_user' => (int)$_REQUEST['id_user'] ) ))
        {
            throw new SmartViewException('Operation denied');
        }    
       
        // lock the user to edit
        $result = $this->model->action('user','lock',
                                       array('job'        => 'lock',
                                             'id_user'    => (int)$_REQUEST['id_user'],
                                             'by_id_user' => (int)$this->viewVar['loggedUserId']) );
        if($result !== TRUE)
        {
            // this would only happen if someone try to hack a tournaround
            throw new SmartViewException('Operation denied. User is locked by: '.$result);    
        }
    }
    
    /**
     * Modify user data
     *
     * @return bool true on success else false
     */
    public function perform()
    { 
        // init template array to fill with user data
        $this->tplVar['user'] = array();
        // Init template form field values
        $this->tplVar['error']            = array();
        $this->tplVar['user']['email']       = '';
        $this->tplVar['user']['login']       = '';
        $this->tplVar['user']['passwd']      = '';
        $this->tplVar['user']['name']        = '';
        $this->tplVar['user']['lastname']    = '';  
        $this->tplVar['user']['description'] = '';   
        $this->tplVar['user']['user_gmt']    = 1;  
        $this->tplVar['user']['role']        = 0;  
        $this->tplVar['user']['thumb']       = array();
        $this->tplVar['user']['file']        = array();
       
        // update user data
        if( isset($_POST['updatethisuser']) )
        {
            if(FALSE == $this->updateUserData())
            {
                return FALSE;
            }
        }

        // get user data
        $this->model->action('user','getUser',
                             array('result'  => & $this->tplVar['user'],
                                   'id_user' => (int)$_REQUEST['id_user'],
                                   'fields'  => array('login',
                                                      'name',
                                                      'lastname',
                                                      'email',
                                                      'status',
                                                      'role',
                                                      'description',
                                                      'user_gmt',
                                                      'format',
                                                      'logo',
                                                      'media_folder')) );
        
        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['user'], array('name','lastname') );

        $this->tplVar['user']['thumb'] = array();

        // get user picture thumbnails
        $this->model->action('user','getAllThumbs',
                             array('result'  => & $this->tplVar['user']['thumb'],
                                   'id_user' => (int)$_REQUEST['id_user'],
                                   'order'   => 'rank',
                                   'fields'  => array('id_pic',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'height',
                                                      'width',
                                                      'title',
                                                      'description')) );

        // convert description field to safely include into javascript function call
        $x=0;
        $this->tplVar['user']['thumbdesc'] = array();
        foreach($this->tplVar['user']['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['user']['thumb'][$x], array('description') );
            $x++;
        }

        $this->tplVar['user']['file'] = array();

        // get user files
        $this->model->action('user','getAllFiles',
                             array('result'  => & $this->tplVar['user']['file'],
                                   'id_user' => (int)$_REQUEST['id_user'],
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        $this->tplVar['user']['filedesc'] = array();
        foreach($this->tplVar['user']['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['user']['file'][$x], array('description') );
            $x++;
        }

        // assign some template variables
        $this->setTemplateVars();
    } 
    
    /**
     * Update user data
     *
     */
    private function updateUserData()
    {
        // check permission to set user role except if a logged user modify its own data.
        // In this case he cant modify its own role so we dont check this permission
        if(isset($_POST['role']) && (FALSE == $this->checkAssignedPermission( (int)$_POST['role'] )))
        {
            $this->resetFormData();
            $this->tplVar['error'] = 'You have no rights to assign the such role to a new user!';
            $this->setTemplateVars();
            return FALSE;
        }
         // cancel edit user?
        elseif($_POST['canceledit'] == '1')
        {
            $this->unlockUser();
            $this->redirect();
        }
        // delete a user?
        elseif($_POST['deleteuser'] == '1')
        {
            $this->deleteUser();
        }      
        // upload logo
        elseif(isset($_POST['uploadlogo']) && !empty($_POST['uploadlogo']))
        {   
            $this->model->action('user','uploadLogo',
                                 array('id_user'  => (int)$_REQUEST['id_user'],
                                       'postName' => 'logo',
                                       'error'    => & $this->tplVar['error']) ); 
                                        
            $dont_forward = TRUE;
        }
        // delete logo
        elseif(isset($_POST['deletelogo']) && !empty($_POST['deletelogo']))
        {   
            $this->model->action('user','deleteLogo',
                                 array('id_user'   => (int)$_REQUEST['id_user']) ); 
                                         
            $dont_forward = TRUE;
        }   
        // add picture
        elseif(isset($_POST['uploadpicture']) && !empty($_POST['uploadpicture']))
        {   
            $this->model->action('user','addItem',
                                 array('item'     => 'picture',
                                       'id_user'  => (int)$_REQUEST['id_user'],
                                       'postName' => 'picture',
                                       'error'    => & $this->tplVar['error']) ); 
                                         
            $dont_forward = TRUE;
        }
        // delete picture
        elseif(isset($_POST['imageID2del']) && !empty($_POST['imageID2del']))
        {
            $this->model->action('user','deleteItem',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_pic'  => (int)$_POST['imageID2del']) ); 
                                         
            $dont_forward = TRUE;
        }
        // move image rank up
        elseif(isset($_POST['imageIDmoveUp']) && !empty($_POST['imageIDmoveUp']))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_pic'  => (int)$_POST['imageIDmoveUp'],
                                       'dir'     => 'up') ); 
                                         
            $dont_forward = TRUE;
        }  
        // move image rank down
        elseif(isset($_POST['imageIDmoveDown']) && !empty($_POST['imageIDmoveDown']))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_pic'  => (int)$_POST['imageIDmoveDown'],
                                       'dir'     => 'down') ); 
                                         
            $dont_forward = TRUE;
        } 
        // move file rank up
        elseif(isset($_POST['fileIDmoveUp']) && !empty($_POST['fileIDmoveUp']))
        {
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_file' => (int)$_POST['fileIDmoveUp'],
                                       'dir'     => 'up') );                                                 
            $dont_forward = TRUE;
        }
        // move file rank down
        elseif(isset($_POST['fileIDmoveDown']) && !empty($_POST['fileIDmoveDown']))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_file' => (int)$_POST['fileIDmoveDown'],
                                       'dir'     => 'down') );                                                
            $dont_forward = TRUE;
        } 
        // add file
        elseif(isset($_POST['uploadfile']) && !empty($_POST['uploadfile']))
        {          
            $this->model->action('user','addItem',
                                 array('item'     => 'file',
                                       'id_user'  => (int)$_REQUEST['id_user'],
                                       'postName' => 'ufile',
                                       'error'    => & $this->tplVar['error']) ); 
                                     
            $dont_forward = TRUE;
        }
        // delete file
        elseif(isset($_POST['fileID2del']) && !empty($_POST['fileID2del']))
        {   
            $this->model->action('user','deleteItem',
                                 array('id_user' => (int)$_REQUEST['id_user'],
                                       'id_file' => (int)$_POST['fileID2del']) ); 
                                         
            $dont_forward = TRUE;
        }  
        
        // update picture descriptions if there images
        if(isset($_POST['pid']))
        {
            $this->model->action( 'user','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$_POST['pid'],
                                        'fields'  => array('description' => $this->stripSlashesArray($_POST['picdesc']),
                                                           'title'       => $this->stripSlashesArray($_POST['pictitle']))));
        }        

        // update file descriptions if there file attachments
        if(isset($_POST['fid']))
        {
            $this->model->action( 'user','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$_POST['fid'],
                                        'fields'  => array('description' => $this->stripSlashesArray($_POST['filedesc']),
                                                           'title'       => $this->stripSlashesArray($_POST['filetitle']))));
        }  
       
        // check if required fields are empty
        if (FALSE == $this->checkEmptyFields())
        {
            // reset form fields on error
            $this->resetFormData();
            $this->tplVar['error'][] = 'You have fill out at least the name, lastname and email fields!';
            $this->setTemplateVars();
            return FALSE;
        }
           
        // array with new user data passed to the action
        $_data = array( 'error'     => & $this->tplVar['error'],
                        'id_user'   => (int)$_REQUEST['id_user'],
                        'fields' => array('email'    => SmartCommonUtil::stripSlashes((string)$_POST['email']),
                                          'name'     => SmartCommonUtil::stripSlashes((string)$_POST['name']),
                                          'lastname' => SmartCommonUtil::stripSlashes((string)$_POST['lastname']),
                                          'description' => SmartCommonUtil::stripSlashes((string)$_POST['description']) ));

        if( isset($_POST['user_gmt']) )
        {
            if( ($_POST['user_gmt'] >= -12) &&  ($_POST['user_gmt'] <= 12) )
            {
                $_data['fields']['user_gmt'] = (int)$_POST['user_gmt'];
                // update session user gmt
                $this->model->session->set('loggedUserGmt', (int)$_POST['user_gmt']);
            }        
        }

        // if a logged user modify its own account data disable status and role settings
        if($this->viewVar['loggedUserId'] != $_REQUEST['id_user'])
        {
            $_data['fields']['status'] = (int)$_POST['status']; 
            $_data['fields']['role']   = (int)$_POST['role'];
        }
        // add this if the password field isnt empty
        if(!empty($_POST['passwd']))
        {
            $_data['fields']['passwd'] = SmartCommonUtil::stripSlashes((string)$_POST['passwd']);
        }
        
        // add new user data
        if(TRUE == $this->model->action( 'user','update',$_data ))
        {
            if(isset($_POST['updateuser']) && ($_POST['updateuser'] == 'Submit'))
            {
                $this->unlockUser();
                $this->redirect();
            }
            return TRUE;
        }
        else
        {
            // reset form fields on error
            $this->resetFormData();
            $this->setTemplateVars();
            return FALSE;                
        }        
    }
    
    /**
     * Delete user
     *
     */
    private function deleteUser()
    {
        // not possible if a logged user try to remove it self
        if($this->viewVar['loggedUserId'] != $_REQUEST['id_user'])
        {                                 
            $this->model->action('user','delete',
                                 array('id_user' => (int)$_REQUEST['id_user']));                      
            $this->redirect();   
        }   
    }

    /**
     * Redirect to the main user location
     */
    private function redirect()
    {
        // reload the user module
        @header('Location: '.$this->model->baseUrlLocation.'/'.JAPA_CONTROLLER.'?mod=user');
        exit;      
    }

    /**
     * assign some template variables
     *
     */
    private function setTemplateVars()
    {
        // Assign template variable to build the html role select box
        $this->assignHtmlSelectBoxRole();
        
        // assign template var if the logged user edit his own account
        if($this->viewVar['loggedUserId'] == $_REQUEST['id_user'])
        {  
            // dont show some form elements (delete,status,role)
            $this->tplVar['showButton'] = FALSE;  
        }
        else
        {
            $this->tplVar['showButton'] = TRUE;  
        }  
        
        //  -----  feature for a next release ------------
        // set format template var, means how to format textarea content -> editor/wikki ?
        // 1 = text_wikki
        // 2 = tiny_mce
        if($this->config['user']['force_format'] != 0)
        {
            $this->tplVar['format'] = $this->config['user']['force_format'];
            $this->tplVar['show_format_switch'] = FALSE;
        }
        elseif(isset($_POST['format']))
        {
            if(!preg_match("/(1|2){1}/",$_POST['format']))
            {
                $this->tplVar['format'] = $this->config['user']['default_format'];
            }
            $this->tplVar['format'] = $_POST['format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        else
        {
            $this->tplVar['format'] = $this->config['user']['default_format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        
        
        $this->tplVar['id_user'] = $_REQUEST['id_user']; 
    }

    /**
     * Convert strings so that they can be safely included in html forms
     *
     * @param array $var_array Associative array
     * @param array $fields Field names
     */
    private function convertHtmlSpecialChars( &$var_array, $fields )
    {
        foreach($fields as $f)
        {
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config['charset'] );
        }
    }

    /**
     * strip slashes from form fields
     *
     * @param array $var_array Associative array
     */
    private function stripSlashesArray( &$var_array)
    {
        $tmp_array = array();
        foreach($var_array as $f)
        {
            $tmp_array[] = preg_replace("/\"/","'",SmartCommonUtil::stripSlashes( $f ));
        }

        return $tmp_array;
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
            // just the roles on which the logged user have rights
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
        if($this->viewVar['loggedUserRole'] < 100)
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
        if( empty($_POST['email']) || 
            empty($_POST['lastname']) || 
            empty($_POST['name']) )
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
        // if empty assign form field with old values
        $this->tplVar['user']['role']     = (int)$_POST['role'];
        $this->tplVar['user']['email']    = SmartCommonUtil::stripSlashes((string)$_POST['email']);
        $this->tplVar['user']['name']     = SmartCommonUtil::stripSlashes((string)$_POST['name']);
        $this->tplVar['user']['lastname'] = SmartCommonUtil::stripSlashes((string)$_POST['lastname']);
        $this->tplVar['user']['description'] = SmartCommonUtil::stripSlashes((string)$_POST['description']);
        $this->tplVar['user']['login']    = SmartCommonUtil::stripSlashes((string)$_POST['login']);
        $this->tplVar['user']['passwd']   = SmartCommonUtil::stripSlashes((string)$_POST['passwd']); 
        $this->tplVar['user']['status']   = (int)$_POST['status']; 
        $this->tplVar['user']['user_gmt'] = (int)$_POST['user_gmt']; 
    } 

    /**
     * unlock edited user
     *
     */     
    private function unlockUser()
    {
        $this->model->action('user','lock',
                             array('job'     => 'unlock',
                                   'id_user' => (int)$_REQUEST['id_user']));    
    }
}

?>