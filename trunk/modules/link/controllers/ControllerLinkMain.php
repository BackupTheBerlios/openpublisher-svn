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
 * ControllerLinkMain
 *
 */
 
class ControllerLinkMain extends JapaControllerAbstractPage
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

        // get node related links
        $this->model->action('link','getLinks', 
                             array('result'  => & $this->viewVar['links'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','url','id_link',
                                                      'description','status')));

        // get link locks
        $this->getLocks();
    }  
 
    /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // set template variable to show edit links        
        $this->viewVar['showLink'] = $this->allowModify();    
        
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
        
        if($this->current_id_node == 0)
        {
            $this->viewVar['showAddLink'] = FALSE;        
        }
        else
        {
            $this->viewVar['showAddLink'] = TRUE;
        }

        // template variables
        //
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the child nodes
        $this->viewVar['nodes']  = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // data of the node links
        $this->viewVar['links'] = array(); 
        // links to the next/previous pages
        $this->viewVar['pageLinks'] = '';
        // errors
        $this->viewVar['error']  = FALSE;    
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] < 100 )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
     /**
     * assign template variables with lock status of each link
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['links'] as $link)
        {
            // lock the user to edit
            $result = $this->model->action('link','lock',
                                     array('job'        => 'is_locked',
                                           'id_link'    => (int)$link['id_link'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['links'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['links'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }       
}

?>