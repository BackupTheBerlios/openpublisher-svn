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
 * ControllerKeywordMain
 *
 */
 
class ControllerKeywordMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
   /**
     * current id_key
     * @var int $current_id_key
     */
    private $current_id_key;    
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();
        
        // get current node data if we arent at the top level node
        if($this->current_id_key != 0)
        {
            $this->model->action('keyword','getKeyword', 
                                 array('result' => & $this->viewVar['key'],
                                       'id_key' => (int)$this->current_id_key,
                                       'error'  => & $this->viewVar['error'],
                                       'fields' => array('title','id_key')));        
        }
    
        // get child navigation nodes
        $this->model->action('keyword','getChilds', 
                             array('result'  => & $this->viewVar['keys'],
                                   'id_key'  => (int)$this->current_id_key,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_key','id_parent','status')));
 
        // get navigation node branch of the current node
        $this->model->action('keyword','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_key' => (int)$this->current_id_key,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_key')));                 

        // get keyword locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each node
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['keys'] as $node)
        {
            // lock the user to edit
            $result = $this->model->action('keyword','lock',
                                     array('job'        => 'is_locked',
                                           'id_key'     => (int)$node['id_key'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['keys'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['keys'][$row]['lock'] = FALSE;  
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
        $this->current_id_key = $this->httpRequest->getParameter('id_key', 'request', 'int');
        
        // fetch the current id_key. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $this->current_id_key ) 
        {
            $this->viewVar['id_key']  = 0;
            $this->current_id_key    = 0;      
        }
        else
        {
            $this->viewVar['id_key']  = (int)$this->current_id_key;
            $this->current_id_key    = (int)$this->current_id_key;          
        }   
        
        // template variables
        //
        // data of the current node
        $this->viewVar['key']   = array();
        // data of the child nodes
        $this->viewVar['keys']  = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // errors
        $this->viewVar['error']  = FALSE;    
    }
}

?>