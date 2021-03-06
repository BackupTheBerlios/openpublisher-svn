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
 * ControllerNavigationMain
 *
 */
 
class ControllerNavigationMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
   /**
     * current id_node
     * @var int $current_id_node
     */
    private $current_id_node;    
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();

        // move up or down a node
        if( false !== $this->id_node_up )
        {
            $this->model->action('navigation','moveNodeRank', 
                                 array('id_node' => (int)$this->id_node_up,
                                       'dir'     => 'up'));        
        }
        elseif(  false !== $this->id_node_down )
        {
            $this->model->action('navigation','moveNodeRank', 
                                 array('id_node' => (int)$this->id_node_down,
                                       'dir'     => 'down'));        
        }
        
        // get current node data if we arent at the top level node
        if($this->current_id_node != 0)
        {
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $this->viewVar['node'],
                                       'id_node' => (int)$this->current_id_node,
                                       'error'   => & $this->viewVar['error'],
                                       'fields'  => array('title','id_node')));        
        }
    
        // get child navigation nodes
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->viewVar['nodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node','id_parent','status')));
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));                 

        // get node locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each node
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['nodes'] as $node)
        {
            // lock the user to edit
            $result = $this->model->action('navigation','lock',
                                     array('job'        => 'is_locked',
                                           'id_node'    => (int)$node['id_node'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['nodes'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['nodes'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $this->current_id_node = $this->httpRequest->getParameter('id_node', 'request', 'int');
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $this->current_id_node ) 
        {
            $this->viewVar['id_node']  = 0;
            $this->current_id_node    = 0;      
        }
        else
        {
            $this->viewVar['id_node']  = (int)$this->current_id_node;       
        }    
        
        $this->id_node_up   = $this->httpRequest->getParameter('id_node_up', 'get', 'int');
        $this->id_node_down = $this->httpRequest->getParameter('id_node_down', 'get', 'int');
        
        
        // template variables
        //
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the child nodes
        $this->viewVar['nodes']  = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // errors
        $this->viewVar['error']  = FALSE;    
    }
}

?>