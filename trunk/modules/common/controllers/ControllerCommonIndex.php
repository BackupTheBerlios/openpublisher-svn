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
 * ControllerCommonIndex class
 *
 */

class ControllerCommonIndex extends JapaControllerAbstractPage
{
     /**
     * Login Module to load
     * @var mixed $loginModule
     */
    private $loginModule = false;

     /**
     * Login View to load
     * @var mixed $loginView
     */
    private $loginController = false;
    
    /**
     * Execute the main view
     *
     */
    public function perform()
    {
        // disable main menu ?
        if(false !== $this->router->getVar('disableMainMenu'))
        {
            $this->viewVar['disableMainMenu'] = true;   
        } 
        else
        {
            $this->viewVar['disableMainMenu'] = false;   
        }
       
        // assign some template variables
        $this->viewVar['japaVersionNumber'] = $this->config['japa_version'];
        $this->viewVar['moduleList'] = $this->model->getModuleInfo();
        $this->viewVar['charset']    = $this->config['charset'];
        $this->viewVar['adminCssFolder'] = 'modules/common/css_home';
        $this->viewVar['textarea_rows'] = $this->config['textarea_rows'];
        $this->viewVar['publicWebController'] = $this->config['default_application_controller'];
        $this->viewVar['adminWebController']  = $this->config['default_module_application_controller'];
        
        // assign template var to show the admin header and footer
        // some views dosent need it
        if(false !== $this->router->getVar('nodecoration'))
        {
            $this->viewVar['showHeaderFooter'] = false;
        }
        else
        {
            $this->viewVar['showHeaderFooter'] = true;
        }       
        
        // get url base
        $this->viewVar['url_base'] = $this->httpRequest->getBaseUrl();
        $this->controllerVar['url_base'] = $this->viewVar['url_base']; 
        
        // get requested module controller name
        $module_controller = $this->getRequestedModuleController();
        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        $this->viewVar['module_controller'] = $this->controllerLoader->$module_controller(false, true);  
    }  

    /**
     * Validate view request name.
     *
     * @see dispatch() 
     */
    private function validateViewName( $moduleView, $module, $view )
    {
        if(preg_match("/[^a-zA-Z0-9_]/", $moduleView))
        {
            throw new JapaViewException('Wrong view fromat: ' . $moduleView);
        }

        if(!@file_exists(JAPA_MODULES_DIR . $module . '/views/View' . $moduleView . '.php'))
        {
            throw new JapaViewException('View dosent exists: ' . JAPA_MODULES_DIR . $module . '/views/View' . $moduleView . '.php');
        }
    }
    
    /**
     * authentication
     *
     */
    public function auth()
    {
        // if both variables contain NULL, means that the user isnt authenticated.
        // 
        $this->controllerVar['loggedUserId']   = $this->model->config['loggedUserId'];
        $this->controllerVar['loggedUserRole'] = $this->model->config['loggedUserRole'];
        
        $this->checkPermission();
    }

    /**
     * Check permission to access the admin section
     *
     */
    private function checkPermission()
    {
        // if login user id dosent exists set login target
        if($this->model->config['loggedUserId'] === NULL)
        {
            $this->setLoginTarget();
        }
        
        // User Role flags
        // Admin  = 20
        // Editor = 40
        // Author = 60
        // Webuser = 100
        //
        // Webuser (100) hasnt access to the admin section
        //
        if(($this->model->config['loggedUserRole'] === NULL) || 
           ($this->model->config['loggedUserRole'] >= 100))
        {
            $this->setLoginTarget();
        }
        else
        {
            // set template variable
            $this->viewVar['isUserLogged'] = true;
            $this->viewVar['userRole'] = $this->model->config['loggedUserRole'];
        }    
    }

    /**
     * Set login module name and view name
     *
     */    
    private function setLoginTarget()
    {
        $this->loginModule = 'user';
        $this->loginController   = 'Login';
        // set template variable
        $this->viewVar['isUserLogged'] = false;
    }
    
    /**
     * get requested module controller name
     *
     * @return string
     */
    public function getRequestedModuleController()
    {
        // Set the module which takes the login part
        if($this->loginModule != false)
        {
            $module_request = $this->loginModule; 
        }
        // check if there is a module request
        elseif( false === ($module_request = $this->router->getVar('mod')) )
        {
            $module_request = $this->config['default_module'];
        }

        if($this->loginModule != false)
        {
            $controller_request = $this->loginController;
        }
        // else set the index controller name
        else
        {
            $controller_request = 'Index';
        }

        // build the whole module controller name
        return ucfirst($module_request) . ucfirst($controller_request);                    
    }
}

?>