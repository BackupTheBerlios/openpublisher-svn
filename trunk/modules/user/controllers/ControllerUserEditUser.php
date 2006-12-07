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
 * ControllerUserEditUser class
 *
 */
 
class ControllerUserEditUser extends JapaControllerAbstractPage
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
    public function prependFilterChain()
    {
        $this->id_user = $this->httpRequest->getParameter( 'id_user', 'request', 'digits' );

        // check permission to edit/update requested user data
        if(false == $this->model->action('user','allowEditUser',
                                         array('id_user' => (int)$this->id_user ) ))
        {
            throw new JapaControllerException('Operation denied');
        }    
       
        // lock the user to edit
        $result = $this->model->action('user','lock',
                                       array('job'        => 'lock',
                                             'id_user'    => (int)$this->id_user,
                                             'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
        if($result !== true)
        {
            // this would only happen if someone try to hack a tournaround
            throw new JapaControllerException('Operation denied. User is locked by: '.$result);    
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
        $this->viewVar['user'] = array();
        // Init template form field values
        $this->viewVar['error']            = array();
        $this->viewVar['user']['email']       = '';
        $this->viewVar['user']['login']       = '';
        $this->viewVar['user']['passwd']      = '';
        $this->viewVar['user']['name']        = '';
        $this->viewVar['user']['lastname']    = '';  
        $this->viewVar['user']['description'] = '';   
        $this->viewVar['user']['user_gmt']    = 1;  
        $this->viewVar['user']['role']        = 0;  
        $this->viewVar['user']['thumb']       = array();
        $this->viewVar['user']['file']        = array();

        // update user data
        if( false !== $this->httpRequest->getParameter( 'updatethisuser', 'post', 'alpha' ) )
        {       
            if(false == $this->updateUserData())
            {
                return false;
            }
        }

        // get user data
        $this->model->action('user','getUser',
                             array('result'  => & $this->viewVar['user'],
                                   'id_user' => (int)$this->id_user,
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
        $this->convertHtmlSpecialChars( $this->viewVar['user'], array('name','lastname') );

        $this->viewVar['user']['thumb'] = array();

        // get user picture thumbnails
        $this->model->action('user','getAllThumbs',
                             array('result'  => & $this->viewVar['user']['thumb'],
                                   'id_user' => (int)$this->id_user,
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
        $this->viewVar['user']['thumbdesc'] = array();
        foreach($this->viewVar['user']['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['user']['thumb'][$x], array('description') );
            $x++;
        }

        $this->viewVar['user']['file'] = array();

        // get user files
        $this->model->action('user','getAllFiles',
                             array('result'  => & $this->viewVar['user']['file'],
                                   'id_user' => (int)$this->id_user,
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        $this->viewVar['user']['filedesc'] = array();
        foreach($this->viewVar['user']['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['user']['file'][$x], array('description') );
            $x++;
        }

        // assign some template variables
        $this->setViewVars();
    } 
    
    /**
     * Update user data
     *
     */
    private function updateUserData()
    {
        $role = $this->httpRequest->getParameter( 'role', 'post', 'int' );
        $status = $this->httpRequest->getParameter( 'status', 'post', 'int' );
        $canceledit = $this->httpRequest->getParameter( 'canceledit', 'post', 'digits' );
        $deleteuser = $this->httpRequest->getParameter( 'deleteuser', 'post', 'digits' );
        $uploadlogo = $this->httpRequest->getParameter( 'uploadlogo', 'post', 'alnum' );
        $deletelogo = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
        $uploadpicture = $this->httpRequest->getParameter( 'uploadpicture', 'post', 'alnum' );
        $imageID2del = $this->httpRequest->getParameter( 'imageID2del', 'post', 'raw' );
        $imageIDmoveUp = $this->httpRequest->getParameter( 'imageIDmoveUp', 'post', 'digits' );
        $imageIDmoveDown = $this->httpRequest->getParameter( 'imageIDmoveDown', 'post', 'digits' );      
        $fileIDmoveUp = $this->httpRequest->getParameter( 'fileIDmoveUp', 'post', 'digits' );
        $fileIDmoveDown = $this->httpRequest->getParameter( 'fileIDmoveDown', 'post', 'digits' );        
        $uploadfile = $this->httpRequest->getParameter( 'uploadfile', 'post', 'alnum' );
        $fileID2del = $this->httpRequest->getParameter( 'fileID2del', 'post', 'digits' );
        $pid = $this->httpRequest->getParameter( 'pid', 'post', 'raw' );
        $fid = $this->httpRequest->getParameter( 'fid', 'post', 'raw' );
        $email = $this->httpRequest->getParameter( 'email', 'post', 'email' );
        $name = $this->httpRequest->getParameter( 'name', 'post', 'raw' );
        $passwd = $this->httpRequest->getParameter( 'passwd', 'post', 'alnum' );
        $lastname = $this->httpRequest->getParameter( 'lastname', 'post', 'raw' );
        $description = $this->httpRequest->getParameter( 'description', 'post', 'raw' );
        $user_gmt = $this->httpRequest->getParameter( 'user_gmt', 'post', 'int' );
        
        // check permission to set user role except if a logged user modify its own data.
        // In this case he cant modify its own role so we dont check this permission
        if((false !== $role) && (false == $this->checkAssignedPermission( (int)$role )))
        {
            $this->resetFormData();
            $this->viewVar['error'] = 'You have no rights to assign the such role to a new user!';
            $this->setTemplateVars();
            return false;
        }
         // cancel edit user?
        elseif($canceledit == '1')
        {
            $this->unlockUser();
            $this->redirect();
        }
        // delete a user?
        elseif($deleteuser == '1')
        {
            $this->deleteUser();
        }      
        // upload logo
        elseif(!empty($uploadlogo))
        {   
            $logo = $this->httpRequest->getParameter( 'logo', 'files', 'raw' );

            $this->model->action('user','uploadLogo',
                                 array('id_user'  => (int)$this->id_user,
                                       'postData' => & $logo,
                                       'error'    => & $this->viewVar['error']) ); 
                                        
            $dont_forward = true;
        }
        // delete logo
        elseif(!empty($deletelogo))
        {   
            $this->model->action('user','deleteLogo',
                                 array('id_user'   => (int)$this->id_user) ); 
                                         
            $dont_forward = true;
        }   
        // add picture
        elseif(!empty($uploadpicture))
        {   
            $picture = $this->httpRequest->getParameter( 'picture', 'files', 'raw' );
            $this->model->action('user','addItem',
                                 array('item'     => 'picture',
                                       'id_user'  => (int)$this->id_user,
                                       'postData' => &$picture,
                                       'error'    => & $this->viewVar['error']) ); 
                                         
            $dont_forward = true;
        }
        // delete picture
        elseif(!empty($imageID2del))
        {
            $this->model->action('user','deleteItem',
                                 array('id_user' => (int)$this->id_user,
                                       'id_pic'  => (int)$imageID2del) ); 
                                         
            $dont_forward = true;
        }
        // move image rank up
        elseif(!empty($imageIDmoveUp))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$this->id_user,
                                       'id_pic'  => (int)$imageIDmoveUp,
                                       'dir'     => 'up') ); 
                                         
            $dont_forward = true;
        }  
        // move image rank down
        elseif(!empty($imageIDmoveDown))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$this->id_user,
                                       'id_pic'  => (int)$imageIDmoveDown,
                                       'dir'     => 'down') ); 
                                         
            $dont_forward = true;
        } 
        // move file rank up
        elseif(!empty($fileIDmoveUp))
        {
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$this->id_user,
                                       'id_file' => (int)$fileIDmoveUp,
                                       'dir'     => 'up') );                                                 
            $dont_forward = true;
        }
        // move file rank down
        elseif(!empty($fileIDmoveDown))
        {   
            $this->model->action('user','moveItemRank',
                                 array('id_user' => (int)$this->id_user,
                                       'id_file' => (int)$fileIDmoveDown,
                                       'dir'     => 'down') );                                                
            $dont_forward = true;
        } 
        // add file
        elseif(!empty($uploadfile))
        {   
            $ufile = $this->httpRequest->getParameter( 'ufile', 'files', 'raw' ); 
            $this->model->action('user','addItem',
                                 array('item'     => 'file',
                                       'id_user'  => (int)$this->id_user,
                                       'postData' => &$ufile,
                                       'error'    => & $this->viewVar['error']) ); 
                                     
            $dont_forward = true;
        }
        // delete file
        elseif(!empty($fileID2del))
        {   
            $this->model->action('user','deleteItem',
                                 array('id_user' => (int)$this->id_user,
                                       'id_file' => (int)$fileID2del) ); 
                                         
            $dont_forward = true;
        }  
        
        // update picture descriptions if there images
        if(!empty($pid))
        {
            $picdesc = $this->httpRequest->getParameter( 'picdesc', 'post', 'raw' );
            $pictitle = $this->httpRequest->getParameter( 'pictitle', 'post', 'raw' );
            $this->model->action( 'user','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$pid,
                                        'fields'  => array('description' => $this->stripSlashesArray($picdesc),
                                                           'title'       => $this->stripSlashesArray($pictitle))));
        }        

        // update file descriptions if there file attachments
        if(!empty($fid))
        {
            $filedesc = $this->httpRequest->getParameter( 'filedesc', 'post', 'raw' );
            $filetitle = $this->httpRequest->getParameter( 'filetitle', 'post', 'raw' );
            $this->model->action( 'user','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$fid,
                                        'fields'  => array('description' => $this->stripSlashesArray($filedesc),
                                                           'title'       => $this->stripSlashesArray($filetitle))));
        }  
       
        // check if required fields are empty
        if (false == $this->checkEmptyFields())
        {
            // reset form fields on error
            $this->resetFormData();
            $this->viewVar['error'][] = 'You have fill out at least the name, lastname and email fields!';
            $this->setTemplateVars();
            return false;
        }
           
        // array with new user data passed to the action
        $_data = array( 'error'     => & $this->viewVar['error'],
                        'id_user'   => (int)$this->id_user,
                        'fields' => array('email'    => JapaCommonUtil::stripSlashes($email),
                                          'name'     => JapaCommonUtil::stripSlashes($name),
                                          'lastname' => JapaCommonUtil::stripSlashes($lastname),
                                          'description' => JapaCommonUtil::stripSlashes($description) ));

        if( !empty($user_gmt) )
        {
            if( ($user_gmt >= -12) &&  ($user_gmt <= 12) )
            {
                $_data['fields']['user_gmt'] = (int)$user_gmt;
                // update session user gmt
                $this->model->session->set('loggedUserGmt', (int)$user_gmt);
            }        
        }

        // if a logged user modify its own account data disable status and role settings
        if($this->controllerVar['loggedUserId'] != $this->id_user)
        {
            $_data['fields']['status'] = (int)$status; 
            $_data['fields']['role']   = (int)$role;
        }
        // add this if the password field isnt empty
        if(!empty($passwd))
        {
            $_data['fields']['passwd'] = JapaCommonUtil::stripSlashes((string)$passwd);
        }
        
        // add new user data
        if(true == $this->model->action( 'user','update',$_data ))
        {
            if('Submit' == $this->httpRequest->getParameter( 'updateuser', 'post', 'alpha' ))
            {
                $this->unlockUser();
                $this->redirect();
            }
            return true;
        }
        else
        {
            // reset form fields on error
            $this->resetFormData();
            $this->setTemplateVars();
            return false;                
        }        
    }
    
    /**
     * Delete user
     *
     */
    private function deleteUser()
    {
        // not possible if a logged user try to remove it self
        if($this->controllerVar['loggedUserId'] != $this->id_user)
        {                                 
            $this->model->action('user','delete',
                                 array('id_user' => (int)$this->id_user));                      
            $this->redirect();   
        }   
    }

    /**
     * Redirect to the main user location
     */
    private function redirect()
    {
        // reload the user module 
        $this->router->redirect($this->viewVar['adminWebController'].'/mod/user');    
    }

    /**
     * assign some template variables
     *
     */
    private function setViewVars()
    {
        // Assign template variable to build the html role select box
        $this->assignHtmlSelectBoxRole();
        
        $this->viewVar['show_options_link'] = false;
        
        // assign template var if the logged user edit his own account
        if($this->controllerVar['loggedUserId'] == $this->id_user)
        {  
            // dont show some form elements (delete,status,role)
            $this->viewVar['showButton'] = false;  
        }
        else
        {
            $this->viewVar['showButton'] = true;  
        }  
        
        $this->viewVar['id_user'] = $this->id_user; 
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
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config->getModuleVar('common','charset') );
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
            $tmp_array[] = preg_replace("/\"/","'",JapaCommonUtil::stripSlashes( $f ));
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
        
        $this->viewVar['form_roles'] = array();
        
        foreach($roles as $key => $val)
        {
            // just the roles on which the logged user have rights
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
            return false;
        }
        return true;
    }

    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->controllerVar['loggedUserRole'] < 100)
        {
            return true;
        }
        return false;
    }
    
    /**
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    private function checkEmptyFields()
    {
        return true;
    }  
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->viewVar['user']['role'] = $this->httpRequest->getParameter( 'role', 'post', 'int' );
        $this->viewVar['user']['status'] = $this->httpRequest->getParameter( 'status', 'post', 'int' );
        $canceledit = $this->httpRequest->getParameter( 'canceledit', 'post', 'digits' );
        $deleteuser = $this->httpRequest->getParameter( 'deleteuser', 'post', 'digits' );
        $uploadlogo = $this->httpRequest->getParameter( 'uploadlogo', 'post', 'alnum' );
        $deletelogo = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
        $uploadpicture = $this->httpRequest->getParameter( 'uploadpicture', 'post', 'alnum' );
        $imageID2del = $this->httpRequest->getParameter( 'imageID2del', 'post', 'raw' );
        $imageIDmoveUp = $this->httpRequest->getParameter( 'imageIDmoveUp', 'post', 'digits' );
        $imageIDmoveDown = $this->httpRequest->getParameter( 'imageIDmoveDown', 'post', 'digits' );      
        $fileIDmoveUp = $this->httpRequest->getParameter( 'fileIDmoveUp', 'post', 'digits' );
        $fileIDmoveDown = $this->httpRequest->getParameter( 'fileIDmoveDown', 'post', 'digits' );        
        $uploadfile = $this->httpRequest->getParameter( 'uploadfile', 'post', 'alnum' );
        $fileID2del = $this->httpRequest->getParameter( 'fileID2del', 'post', 'digits' );
        $pid = $this->httpRequest->getParameter( 'pid', 'post', 'raw' );
        $fid = $this->httpRequest->getParameter( 'fid', 'post', 'raw' );
        $this->viewVar['user']['email'] = $this->httpRequest->getParameter( 'email', 'post', 'email' );
        $this->viewVar['user']['name'] = $this->httpRequest->getParameter( 'name', 'post', 'alnum' );
        $this->viewVar['user']['passwd'] = $this->httpRequest->getParameter( 'passwd', 'post', 'alnum' );
        $this->viewVar['user']['lastname'] = $this->httpRequest->getParameter( 'description', 'post', 'alnum' );
        $this->viewVar['user']['description'] = JapaCommonUtil::stripSlashes($this->httpRequest->getParameter( 'description', 'post', 'raw' ));
        $this->viewVar['user']['user_gmt'] = $this->httpRequest->getParameter( 'user_gmt', 'post', 'int' );
        
        
        // if empty assign form field with old values
        $this->viewVar['user']['login']    = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
    } 

    /**
     * unlock edited user
     *
     */     
    private function unlockUser()
    {
        $this->model->action('user','lock',
                             array('job'     => 'unlock',
                                   'id_user' => (int)$this->id_user));    
    }
}

?>