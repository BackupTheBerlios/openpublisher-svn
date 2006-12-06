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
 * ViewKeywordIndex
 *
 */
 
class ControllerKeywordIndex extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;

    /**
     * Perform on the index view
     */
    public function perform()
    {
        $this->viewVar['requestedModule']   = 'keyword';
        
        // set template var to show user options link
        // only on user main page and if the user role is at least an "admin"
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            $this->viewVar['show_admin_link'] = FALSE;
        }
        else
        {
            $this->viewVar['show_admin_link'] = TRUE;
        }
        
        // get requested module controller name
        $module_controller = $this->getRequestedModuleController();

        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        $this->viewVar['module_keyword_controller'] = $this->controllerLoader->$module_controller(); 
    }  
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        $this->controller_request = $this->router->getVar('cntr');
        
        // all accounts can access the map view
        if( 'map' !== $this->controller_request )
        {
            $info = $this->model->getModuleInfo('keyword');
            // only administrators can access keyword module
            if($this->controllerVar['loggedUserRole'] > $info['perm'])
            {
                // reload admin
                $this->redirect($this->viewVar['adminWebController']);  
            }
        }
    }    
    /**
     * get requested module controller name
     *
     * @return string
     */
    public function getRequestedModuleController()
    {
        // check if there is a module request
        if( false === $this->controller_request )
        {
            $this->controller_request = 'Main';
        }
        elseif($this->controllerVar['loggedUserRole'] > 20)
        {
            $this->viewVar['show_options_link'] = false;
        }

        // build the whole module controller name
        return 'Keyword' . ucfirst($this->controller_request);                    
    }
}

?>