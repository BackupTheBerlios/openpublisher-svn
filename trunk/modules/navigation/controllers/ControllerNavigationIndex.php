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
 * ControllerNavigationIndex
 *
 */
 
class ControllerNavigationIndex extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;

    /**
     * 
     *
     */
    function perform()
    {
        $this->viewVar['requestedModule']   = 'navigation';
        $this->viewVar['show_options_link'] = true;
                
        // get requested module controller name
        $module_controller = $this->getRequestedModuleController();

        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        $this->viewVar['module_navigation_controller'] = $this->controllerLoader->$module_controller();  
    }  
    /**
     * get requested module controller name
     *
     * @return string
     */
    public function getRequestedModuleController()
    {
        // check if there is a module request
        if( false === ($controller_request = $this->router->getVar('cntr')) )
        {
            $controller_request = 'Main';
        }
        elseif($this->controllerVar['loggedUserRole'] > 20)
        {
            $this->viewVar['show_options_link'] = false;
        }

        // build the whole module controller name
        return 'Navigation' . ucfirst($controller_request);                    
    }
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // only administrators can access navigation module
        if($this->controllerVar['loggedUserRole'] > $this->model->config['module']['navigation']['perm'])
        {
            // reload admin
            @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController']);
            exit;  
        }
    }     
}

?>