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
    * Perform 
    *
    */
    public function perform()
    {
        $register   = $this->httpRequest->getParameter('register', 'request', 'alnum');
        $unregister = $this->httpRequest->getParameter('unregister', 'request', 'alnum');
        
        if(!empty($register))
        {
            $availablecontroller = $this->httpRequest->getParameter('availablecontroller', 'post', 'raw');
            
            if(!empty($availablecontroller) && is_array($availablecontroller))
            {
                foreach($availablecontroller as $name)
                {
                    $this->model->action('navigation','registerControllers',
                                         array('action' => 'register',
                                               'name'   => (string)$name) );  
                }
            }
        }
        elseif(!empty($unregister))
        {
            $registeredcontrolller = $this->httpRequest->getParameter('registeredcontroller', 'post', 'raw');
            
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
}

?>