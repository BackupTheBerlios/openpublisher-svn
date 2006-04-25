<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewRegister class
 */

class ViewRegister extends SmartView
{   
    /**
     * Execute the login view
     */
    public function perform()
    {
        $this->initVars();
        
        // create capcha picture and public key
        $this->model->action( 'common','captchaMake',
                              array( 'captcha_pic' => &$this->tplVar['captcha_pic'],
                                     'public_key'  => &$this->tplVar['public_key'],
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
            $this->tplVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->tplVar['isUserLogged'] = TRUE;
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
        $this->tplVar['captcha_pic'] = '';
        $this->tplVar['public_key']  = '';
        $this->tplVar['login']       = '';
        
        // template var with charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
    }  
    /**
     * reset form data
     *
     */     
    private function resetFormData()
    {
        $this->tplVar['login'] = htmlentities(strip_tags(SmartCommonUtil::stripSlashes($_POST['login'])));     
    }     
}

?>