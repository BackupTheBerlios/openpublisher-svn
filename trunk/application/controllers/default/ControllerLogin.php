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
 * ControllerLogin class
 */

class ControllerLogin extends JapaControllerAbstractPage
{   
    /**
     * Execute the login controller
     */
    public function perform()
    {
        $this->initVars();

        
        // get result of the header and footer controller
        //       
        $this->viewVar['header']      = $this->controllerLoader->header();
        $this->viewVar['footer']      = $this->controllerLoader->footer();  
        $this->viewVar['rightBorder'] = $this->controllerLoader->rightBorder(); 
       
        // create capcha picture and public key
        $this->model->action( 'common','captchaMake',
                              array( 'captcha_pic' => &$this->viewVar['captcha_pic'],
                                     'picture_folder' => $this->router->getBase().'/data/common/captcha',
                                     'public_key'  => &$this->viewVar['public_key'],
                                     'configPath'  => $this->config->getVar('config_path')));

        $dologin = $this->httpRequest->getParameter('dologin', 'post', 'alpha');
                    
        // Check login data
        if(!empty($dologin))
        {
            $captcha_turing_key = $this->httpRequest->getParameter('captcha_turing_key', 'post', 'alnum');
            $captcha_public_key = $this->httpRequest->getParameter('captcha_public_key', 'post', 'alnum');
            
            $login_name = $this->httpRequest->getParameter('login_name', 'post', 'alnum');
            $password   = $this->httpRequest->getParameter('password', 'post', 'alnum');     
            
            // validate captcha turing/public keys
            if (FALSE == $this->model->action( 'common','captchaValidate',
                                               array('turing_key'  => (string)$captcha_turing_key,
                                                     'public_key'  => (string)$captcha_public_key,
                                                     'configPath'  => (string)$this->config->getVar('config_path'))))
            {
                $this->resetFormData($login_name);
                return TRUE;
            }
            
            // verify user and password. If those dosent match
            // reload the login page
            $login = $this->model->action( 'user','checkLogin',
                                           array('login'  => (string)$login_name,
                                                 'passwd' => (string)$password));
            
            // If login was successfull reload the destination page
            if($login == TRUE)
            {
                ob_clean();
                // get url vars to switch to the destination page
                $url = $this->model->session->get('url');
                $this->model->session->del('url');
                $this->router->redirect( $url );          
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
        $this->viewVar['charset']   = $this->config->getModuleVar('common', 'charset');
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller');        
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        $this->viewVar['urlBase'] = $this->httpRequest->getBaseUrl();
        $this->viewVar['urlCss'] = 'http://'.$this->router->getHost().$this->viewVar['urlBase'].'/'.$this->viewVar['cssFolder'];  
    }  
    /**
     * reset form data
     *
     */     
    private function resetFormData($login_name)
    {
        $this->viewVar['login'] = $this->model->action( 'common', 'safeHtml', strip_tags(SmartCommonUtil::stripSlashes($login_name)) );  
    }     
}

?>