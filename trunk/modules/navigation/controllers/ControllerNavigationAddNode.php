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
 * ControllerNavigationAddNode
 *
 */
 
class ControllerNavigationAddNode extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;

   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template array to fill with node data
        $this->viewVar['title']  = '';
        $this->viewVar['branch'] = array();  
        $this->viewVar['childs'] = array();
        // Init template form field values
        $this->viewVar['error']            = FALSE;

        $id_node = $this->httpRequest->getParameter('id_node', 'request', 'digits');
        $addnode = $this->httpRequest->getParameter('addnode', 'post', 'alpha');

        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(false === $id_node) 
        {
            $this->viewVar['id_node']  = 0;
            $id_node = 0;
        }
        else
        {
            $this->viewVar['id_node']  = $id_node;
        }
        
        // add node
        if( !empty($addnode) )
        {
            if(FALSE !== ($new_id_node = $this->addNode( $id_node )))
            {
                $this->router->redirect($this->viewVar['adminWebController'].'/mod/navigation/cntr/editNode/id_node/'.$new_id_node); 
            }
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation', 'getChilds', 
                             array('id_node' => (int)$id_node,
                                   'order'   => array('rank', 'asc'),
                                   'status'  => array('>=', 0),
                                   'fields'  => array('id_node','title','status'),
                                   'result'  => & $this->viewVar['childs'],
                                   'error'   => & $this->viewVar['error']));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('navigation',
                             'getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));                 

        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->controllerVar['loggedUserRole'] <= 40)
        {
            $this->viewVar['showAddNodeLink'] = TRUE;
        }
        else
        {
            $this->viewVar['showAddNodeLink'] = FALSE;
        }
    }   
   /**
    * add new node
    *
    * @param int $id_parent parent node of the new node
    */    
    private function addNode( $id_parent )
    {
        $title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        
        if(empty($title))
        {
            $this->viewVar['error'] = 'Title is empty';
            return FALSE;
        }
        
        // init id_controller
        $id_controller = 0;
        // get associated view of the parent node
        if($id_parent != 0)
        {
            $tmp = array();
            // get current node data
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $tmp,
                                       'id_node' => (int)$id_parent,
                                       'fields'  => array('id_controller'))); 
            $id_controller = $tmp['id_controller'];
        }
        
        $new_id_node = $this->model->action('navigation', 'addNode', 
                             array('id_parent' => (int)$id_parent,
                                   'fields'    => array('title'   => JapaCommonUtil::stripSlashes((string)$title),
                                                        'id_controller' => (int)$id_controller,
                                                        'status'  => 1)));    

        // update node related content view                                                        
        $this->model->broadcast('newNodeContentController', 
                                array('id_parent' => (int)$id_parent,
                                      'id_node'   => (int)$new_id_node  ));
        
        return $new_id_node;
    }
}

?>