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
 * ControllerNavigationControllers
 *
 */
 
class ControllerNavigationControllers extends JapaControllerAbstractPage
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
        // if no rights for the logged user, show error template
        // Only administrators 
        if($this->controllerVar['loggedUserRole'] > 20)
        {
            // reload admin
            @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController']);
            exit;  
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        $register   = $this->httpRequest->getParameter('register', 'request', 'alnum');
        $unregister = $this->httpRequest->getParameter('unregister', 'request', 'alnum');
        
        if(!empty($register))
        {
            $availablecontrollers = $this->httpRequest->getParameter('availablecontrollers', 'post', 'raw');
            
            if(!empty($availablecontrollers) && is_array($availablecontrollers))
            {
                foreach($availablecontrollers as $name)
                {
                    $this->model->action('navigation','registerControllers',
                                         array('action' => 'register',
                                               'name'   => (string)$name) );  
                }
            }
        }
        elseif(!empty($unregister))
        {
            $registeredcontrolller = $this->httpRequest->getParameter('registeredcontrolller', 'post', 'raw');
            
            if(!empty($registeredcontrolller) && is_array($registeredcontrolller))
            {
                foreach($registeredcontrolller as $id_controller)
                {
                    $this->model->action('navigation','registerControllers',
                                         array('action'        => 'unregister',
                                               'id_controller' => (int)$id_controller) );  
                }
            }
        }        
        
        // get all available public Controllers
        $this->viewVar['availableControllers'] = array();
        $this->model->action( 'common','getAllPublicControllers',
                              array('result' => &$this->viewVar['availableControllers']) );   
                                    
        // get all registered public Controllers
        $this->viewVar['registeredControllers'] = array();
        $this->model->action( 'navigation','getNodePublicControllers',
                              array('result' => &$this->viewVar['registeredControllers'],
                                    'fields' => array('id_controller','name')) );
        
        return true;
    }   
    
    private function isRegisteredPublicView()
    {
        $this->current_reg_nodes = array();
        $this->model->action( 'navigation','getNodePublicControllers',
                              array('result' => &$this->current_reg_nodes,
                                    'fields' => array('id_controller','name')) );  

        $controller = $this->httpRequest->getParameter('controller', 'post', 'raw');
                                   
        foreach($controller as $c_name)
        {
            foreach($this->current_reg_nodes as $_c)
            {
                if($c_name == $_c['name'])
                {
                    return $_c['id_controller'];
                }
            }
        }
        return false;
    }
}

?>