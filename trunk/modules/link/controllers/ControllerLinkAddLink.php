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
 * ControllerLinkAddLink
 *
 */
 
class ControllerLinkAddLink extends JapaControllerAbstractPage
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
        $this->viewVar['status']      = 1;
        $this->viewVar['title']       = '';
        $this->viewVar['url']         = '';
        $this->viewVar['description'] = '';
        $this->viewVar['branch'] = array();  
        $this->viewVar['childs'] = array();
        $this->viewVar['tree']   = array();
        
        // Init error array
        $this->viewVar['error']  = array();

        $this->current_id_node = $this->httpRequest->getParameter('id_node', 'request', 'int');

        if(false !== $this->current_id_node)
        {
            $this->viewVar['id_node'] = $this->current_id_node;
        }
        else
        {
            $this->viewVar['id_node'] = '0';
        }

        $addlink = $this->httpRequest->getParameter('addlink', 'post', 'alnum');

        // add link
        if( !empty($addlink) )
        {
            $this->link_id_node = $this->httpRequest->getParameter('link_id_node', 'request', 'int');
            $this->link_status = $this->httpRequest->getParameter('status', 'request', 'int');
            $this->link_title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
            $this->link_url = trim($this->httpRequest->getParameter('url', 'post', 'raw'));
            $this->link_description = trim($this->httpRequest->getParameter('description', 'post', 'raw'));
            
            if(TRUE === $this->validate())
            {
                // check if id_node has changed
                if($this->current_id_node != $this->link_id_node)
                {
                    $id_node = $this->link_id_node;
                } 
                else
                {
                    $id_node = $this->current_id_node;
                }
                if(FALSE !== ($new_id_link = $this->addLink( $id_node )))
                {
                    @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/link/id_node/'.$id_node);
                    exit;
                }
            }
            $this->resetFormData();
        }                

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   


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
    private function addLink( $id_node )
    { 
        $this->model->action('link', 'addLink', 
                             array('id_node' => (int)$id_node,
                                   'fields'  => array('title'       => JapaCommonUtil::stripSlashes((string)$this->link_title),
                                                      'url'         => JapaCommonUtil::stripSlashes((string)$this->link_url),
                                                      'description' => JapaCommonUtil::stripSlashes((string)$this->link_description),
                                                      'status'      => (int)$this->link_status)));        
    }
    
    private function validate()
    {
        if(false === $this->current_id_node)
        {
            $this->viewVar['error'][] = '"id_node" isnt defined';
        }

        if($this->link_id_node == 0)
        {
            $this->viewVar['error'][] = '"Top" navigation node isnt allowed';
        }        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(false === $this->link_title) 
        {
            $this->viewVar['error'][] = 'Field "title" isnt defined';
        }
        elseif(!is_string($this->link_title))
        {
            $this->viewVar['error'][] = 'Field "title" isnt from type string';
        }   
        elseif(empty($this->link_title))
        {
            $this->viewVar['error'][] = 'Field "title" is empty';
        }          

        if(false === $this->link_url) 
        {
            $this->viewVar['error'][] = 'Field "url" isnt set';
        }
        elseif(!is_string($this->link_url))
        {
            $this->viewVar['error'][] = 'Field "url" isnt from type string';
        }   
        elseif(empty($this->link_url))
        {
            $this->viewVar['error'][] = 'Field "url" is empty';
        }         

        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * reset the form fields with old link data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->viewVar['status']      = JapaCommonUtil::stripSlashes($this->link_status);
        $this->viewVar['title']       = htmlspecialchars ( JapaCommonUtil::stripSlashes($this->link_title), ENT_COMPAT, $this->config['charset'] );
        $this->viewVar['url']         = htmlspecialchars ( JapaCommonUtil::stripSlashes($this->link_url), ENT_COMPAT, $this->config['charset'] );
        $this->viewVar['description'] = JapaCommonUtil::stripSlashes($this->link_description);         
    }      
}

?>