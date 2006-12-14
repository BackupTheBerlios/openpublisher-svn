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
 * ViewUserLogin class
 *
 */
 
class ControllerUserLogin extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
   /**
     * user log message for this view
     * @var string $logMessage
     */    
    private $logMessage = '';
    
    /**
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    public function perform()
    {
        // init tpl vars
        $this->viewVar['captcha_pic'] = '';
        $this->viewVar['public_key']  = '';
        $this->viewVar['login_name']  = '';
        $this->viewVar['error']       = FALSE;
        
        // create capcha picture and public key
        $this->model->action( 'common',
                              'captchaMake',
                              array( 'captcha_pic'    => &$this->viewVar['captcha_pic'],
                                     'public_key'     => &$this->viewVar['public_key'],
                                     'picture_folder' => $this->controllerVar['url_base'].'/data/common/captcha',
                                     'configPath'     => $this->config->getVar('config_path')));

        $login = $this->httpRequest->getParameter('login', 'post', 'alpha');
                     
        // Check login data
        if($login !== false)
        {
            $captcha_turing_key = $this->httpRequest->getParameter('captcha_turing_key', 'post', 'alnum');
            $captcha_public_key = $this->httpRequest->getParameter('captcha_public_key', 'post', 'alnum');
            
            $login_name = $this->httpRequest->getParameter('login_name', 'post', 'alnum');
            $password   = $this->httpRequest->getParameter('password', 'post', 'alnum');     
                     
            // validate captcha turing/public keys
            if (FALSE == $this->model->action( 'common',
                                               'captchaValidate',
                                               array('turing_key'  => (string)$this->strip($captcha_turing_key),
                                                     'public_key'  => (string)$this->strip($captcha_public_key),
                                                     'configPath'  => (string)$this->config->getVar('config_path'))))
            { 
                $this->_reset_form_data( $login_name );
                return TRUE;
            }
            

            
            // verify user and password. If those dosent match
            // reload the login page
            $login = $this->model->action( 'user','checkLogin',
                                           array('login'  => (string)$this->strip($login_name),
                                                 'passwd' => (string)$this->strip($password )));
            
            // If login was successfull reload the admin section
            if($login == TRUE)
            {
                $this->addLogMessage( "Login: ".(string)$this->strip($login_name) );
                $this->addLogEvent( 1 );
                
                ob_clean();
                $this->router->redirect( $this->config->getVar('default_module_application_controller') );         
            }
        }
            
        return TRUE;
    } 
    
    private function _reset_form_data( $login_name )
    {
        $this->viewVar['login_name'] = htmlentities($this->strip(JapaCommonUtil::stripSlashes($login_name)));     
    }   
    
    /**
     * strip bad code
     *
     */     
    private function strip( $str )
    {
        return $this->model->action( 'common', 'safeHtml', strip_tags( $str ) );   
    }
    
    /**
     * log events of this view
     *
     * for $type values see: /modules/user/actions/ActionUserLogAddEvent.php
     *
     * @param int $type 
     */     
    private function addLogEvent( $type )
    {
        // dont log
        if($this->config->getModuleVar('user', 'use_log') == 0)
        {
            return;
        }
        
        $this->model->action('user','logAddEvent',
                             array('type'    => $type,
                                   'id_item' => 0,
                                   'module'  => 'user',
                                   'controller' => 'login',
                                   'message' => $this->logMessage ));
    }
    
    /**
     * add log message string
     *
     *
     * @param string $message 
     */  
    private function addLogMessage( $message = '' )
    {
        // dont log
        if($this->config->getModuleVar('user', 'use_log') == 0)
        {
            return;
        }
        $this->logMessage .= $message."\n";
    }
}

?>