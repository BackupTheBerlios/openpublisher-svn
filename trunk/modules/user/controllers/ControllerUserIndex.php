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
 * view_user_index class of the template "tpl.user_index.php"
 *
 */
 
class ControllerUserIndex extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
        
    /**
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        $this->viewVar['requestedModule'] = 'user';
                
        // get requested module controller name
        $module_controller = $this->getRequestedModuleController();

        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        $this->viewVar['module_user_controller'] = $this->controllerLoader->$module_controller();  
    }     
    /**
     * get requested module controller name
     *
     * @return string
     */
    public function getRequestedModuleController()
    {
        $this->viewVar['show_options_link'] = true;
        
        // check if there is a module request
        if( (false === ($controller_request = $this->router->getVar('cntr'))) &&
            ($this->controllerVar['loggedUserRole'] <= 20))
        {
            $controller_request = 'Main';
        }
        else
        {
            $this->viewVar['show_options_link'] = false;
        }

        // build the whole module controller name
        return 'User' . ucfirst($controller_request);                    
    }
}

?>