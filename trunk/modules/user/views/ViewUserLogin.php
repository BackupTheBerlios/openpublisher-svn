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
 
class ViewUserLogin extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'login';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/user/templates/';

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
        $this->tplVar['captcha_pic'] = '';
        $this->tplVar['public_key']  = '';
        $this->tplVar['login_name']  = '';
        $this->tplVar['error']       = FALSE;
        
        // create capcha picture and public key
        $this->model->action( 'common',
                              'captchaMake',
                              array( 'captcha_pic' => &$this->tplVar['captcha_pic'],
                                     'public_key'  => &$this->tplVar['public_key'],
                                     'configPath'  => &$this->config['config_path']));
                     
        // Check login data
        if(isset($_POST['login']))
        {
            // validate captcha turing/public keys
            if (FALSE == $this->model->action( 'common',
                                               'captchaValidate',
                                               array('turing_key'  => (string)$this->strip($_POST['captcha_turing_key']),
                                                     'public_key'  => (string)$this->strip($_POST['captcha_public_key']),
                                                     'configPath'  => (string)$this->config['config_path'])))
            { 
                $this->_reset_form_data();
                return TRUE;
            }
            
            // verify user and password. If those dosent match
            // reload the login page
            $login = $this->model->action( 'user','checkLogin',
                                           array('login'  => (string)$this->strip($_POST['login_name']),
                                                 'passwd' => (string)$this->strip($_POST['password'])));
            
            // If login was successfull reload the admin section
            if($login == TRUE)
            {
                $this->addLogMessage( "Login: ".(string)$this->strip($_POST['login_name']) );
                $this->addLogEvent( 1 );
                
                ob_clean();
                @header('Location: ' . $this->config['admin_web_controller']);
                exit;            
            }
        }
            
        return TRUE;
    } 
    
    private function _reset_form_data()
    {
        $this->tplVar['login_name'] = htmlentities($this->strip(SmartCommonUtil::stripSlashes($_POST['login_name'])));     
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
        if($this->config['user']['use_log'] == 0)
        {
            return;
        }
        
        $this->model->action('user','logAddEvent',
                             array('type'    => $type,
                                   'id_item' => 0,
                                   'module'  => 'user',
                                   'view'    => 'login',
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
        if($this->config['user']['use_log'] == 0)
        {
            return;
        }
        $this->logMessage .= $message."\n";
    }
}

?>