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
 * ViewLinkMain
 *
 */
 
class ViewLinkMain extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'main';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/link/templates/';
    
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
                                 array('result'  => & $this->tplVar['node'],
                                       'id_node' => (int)$this->current_id_node,
                                       'error'   => & $this->tplVar['error'],
                                       'fields'  => array('title','id_node')));        
        }
    
        // get child navigation nodes
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->tplVar['nodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node','id_parent','status')));
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                 

        // get node related links
        $this->model->action('link','getLinks', 
                             array('result'  => & $this->tplVar['links'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
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
        $this->tplVar['showLink'] = $this->allowModify();    
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( !isset($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->tplVar['id_node']  = 0;
            $this->current_id_node    = 0;  
        }
        else
        {
            $this->tplVar['id_node']  = (int)$_REQUEST['id_node'];
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }
        
        if($this->current_id_node == 0)
        {
            $this->tplVar['showAddLink'] = FALSE;        
        }
        else
        {
            $this->tplVar['showAddLink'] = TRUE;
        }

        // template variables
        //
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the child nodes
        $this->tplVar['nodes']  = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // data of the node links
        $this->tplVar['links'] = array(); 
        // links to the next/previous pages
        $this->tplVar['pageLinks'] = '';
        // errors
        $this->tplVar['error']  = FALSE;    
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->viewVar['loggedUserRole'] <= 40 )
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
        
        foreach($this->tplVar['links'] as $link)
        {
            // lock the user to edit
            $result = $this->model->action('link','lock',
                                     array('job'        => 'is_locked',
                                           'id_link'    => (int)$link['id_link'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['links'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['links'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }       
}

?>