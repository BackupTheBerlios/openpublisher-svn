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
 * ViewRegister class
 */

class ControllerRegister extends JapaControllerAbstractPage
{   
    /**
     * Execute the login view
     */
    public function perform()
    {
        $this->initVars();
        
        // create capcha picture and public key
        $this->model->action( 'common','captchaMake',
                              array( 'captcha_pic' => &$this->viewVar['captcha_pic'],
                                     'public_key'  => &$this->viewVar['public_key'],
                                     'configPath'  => &$this->config['config_path']));
                     
        // Check login data
        if(isset($_POST['doregister']))
        {
            // validate captcha turing/public keys
            if (FALSE == $this->model->action( 'common','captchaValidate',
                                               array('turing_key'  => (string)$_POST['captcha_turing_key'],
                                                     'public_key'  => (string)$_POST['captcha_public_key'],
                                                     'configPath'  => (string)$this->config['config_path'])))
            {
                $this->resetFormData();
                return TRUE;
            }
            
            // verify user and password. If those dosent match
            // reload the login page
            $login = $this->model->action( 'user','checkLogin',
                                           array('login'  => (string)$_POST['login'],
                                                 'passwd' => (string)$_POST['password']));
            
            // If login was successfull reload the destination page
            if($login == TRUE)
            {
                ob_clean();
                // get url vars to switch to the destination page
                $url = $this->model->session->get('url');
                $this->model->session->del('url');
                @header('Location: '.SMART_CONTROLLER.'?'.$url);
                exit;            
            }
        }
    }
    /**
     * authentication
     *
     */
    public function auth()
    {
        // Check if the visitor is a logged user
        //
        if(NULL == ($this->viewVar['loggedUserId'] = $this->model->session->get('loggedUserId')))
        {
            $this->viewVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->viewVar['isUserLogged'] = TRUE;
        }
        // get user role
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');
    }    
    
    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // init tpl vars
        $this->viewVar['captcha_pic'] = '';
        $this->viewVar['public_key']  = '';
        $this->viewVar['login']       = '';
        
        // template var with charset used for the html pages
        $this->viewVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->viewVar['relativePath'] = SMART_RELATIVE_PATH;
    }  
    /**
     * reset form data
     *
     */     
    private function resetFormData()
    {
        $this->viewVar['login'] = htmlentities(strip_tags(SmartCommonUtil::stripSlashes($_POST['login'])));     
    }     
}

?>