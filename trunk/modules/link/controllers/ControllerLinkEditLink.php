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
 * ControllerLinkEditLink
 *
 */
 
class ControllerLinkEditLink extends JapaControllerAbstractPage
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
     * execute the perform methode
     * @var bool $dontPerform
     */
    private $dontPerform;       
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->viewVar['error'] = 'You have not the rights to edit a link!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        $this->initVars();

        // is node locked by an other user
        if( TRUE !== $this->lockLink() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->viewVar['error'] = 'This link is locked by an other user!';
            $this->dontPerform = TRUE;      
        }
    }        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        if($this->dontPerform == TRUE)
        {
            return;
        }

        $gotonode = $this->httpRequest->getParameter('gotonode', 'request', 'digits');

        // forward to node x without update
        if(!empty($gotonode))
        {
            $this->unlockLink();
            $this->redirect((int)$gotonode);        
        }

        $canceledit = $this->httpRequest->getParameter('canceledit', 'post', 'digits');

        // change nothing and switch back
        if( $canceledit == '1')
        {
            $this->unlockLink();
            $this->redirect((int)$this->current_id_node);        
        }

        $modifylinkdata = $this->httpRequest->getParameter('modifylinkdata', 'post', 'alnum');
        
        if( !empty($modifylinkdata) )
        {
            $this->updateLinkData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   
        
        // get demanded link data
        $this->model->action('link','getLink', 
                             array('result'  => & $this->viewVar['link'],
                                   'id_link' => (int)$this->id_link,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('id_link','title','url',
                                                      'description','status','hits')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->viewVar['link'], array('title','url') );        

        // get node data of this link
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));  
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));                             

        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('link','use_keywords') == 1)
        {
            $addkey = $this->httpRequest->getParameter('addkey', 'request', 'alnum');
            if(!empty($addkey))
            {
                $this->addKeyword();
            }
            $this->getLinkKeywords();
        }
    }  

    private function updateLinkData()
    {
        $link_was_moved  = FALSE;

        // should we remove link related keywords
        $this->deleteLinkKeywords();

        $this->link_id_node = $this->httpRequest->getParameter('link_id_node', 'request', 'int');
        $this->link_status = $this->httpRequest->getParameter('status', 'request', 'int');
        $this->link_title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        $this->link_url = trim($this->httpRequest->getParameter('url', 'post', 'raw'));
        $this->link_description = trim($this->httpRequest->getParameter('description', 'post', 'raw'));

        if(empty($this->link_title))
        {
            $this->viewVar['error'][] = 'Link title is empty!';
        }
        if(empty($this->link_url))
        {
            $this->viewVar['error'][] = 'Url is empty!';
        }

        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        
        if($this->link_id_node == 0)
        {
            $this->viewVar['id_node']  = (int)$this->link_id_node;
            $this->current_id_node    = (int)$this->link_id_node;
            $this->viewVar['error'][] = 'Top node isnt allowed!';
            return FALSE;
        }
        
        // check if id_parent has change
        if($this->id_node != $this->link_id_node)
        {
            $id_node = (string)$this->link_id_node;
            $this->viewVar['id_node']  = (int)$this->link_id_node;
            $this->current_id_node    = (int)$this->link_id_node;
        }
        else
        {
            $id_node = (int)$this->id_node;
        }

        $delete_link = $this->httpRequest->getParameter('delete_link', 'post', 'digits');
            
        if($delete_link == '1')
        {
            if($this->controllerVar['loggedUserRole'] >= 60 )
            {
                return;
            }
            $this->unlockLink();
            $this->deleteLink( $this->id_link, $this->id_node );
            $this->redirect( $this->id_node );
        }                

        // if no error occure update node data
        if(count($this->viewVar['error']) == 0)
        {
            // update node data
            $this->updateLink();
            $finishupdate = $this->httpRequest->getParameter('finishupdate', 'post', 'alnum');
            if( !empty($finishupdate) )
            {
                $this->unlockLink();
                $this->redirect( $id_node );
            }
        }    
    }
     /**
     * is node locked by an other user?
     *
     */   
    private function lockLink()
    {
        return $this->model->action('link','lock',
                                    array('job'        => 'lock',
                                          'id_link'    => (int)$this->id_link,
                                          'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $this->id_node = $this->httpRequest->getParameter('id_node', 'request', 'int');
        $this->id_link = $this->httpRequest->getParameter('id_link', 'request', 'int');
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $this->id_node ) 
        {
            $this->viewVar['id_node']  = 0;
            $this->current_id_node    = 0;      
        }
        else
        {
            $this->viewVar['id_node']  = (int)$this->id_node;
            $this->current_id_node    = (int)$this->id_node;          
        }     

        $this->viewVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // node tree data
        $this->viewVar['tree']   = array();
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // link data
        $this->viewVar['link']  = array();
       
        // errors
        $this->viewVar['error']  = array();   
        
        // use keywords or not
        $this->viewVar['use_keywords'] = $this->config->getModuleVar('link','use_keywords'); 

        if(isset($_REQUEST['disableMainMenu']))
        {
            $this->viewVar['disableMainMenu']  = "1";  
        }
        else
        {
            $this->viewVar['disableMainMenu']  = FALSE;  
        }
        
        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('link','use_keywords') == 1)
        {
            $this->viewVar['opener_url_vars'] = base64_encode('/cntr/editLink/id_link/'.$this->id_link.'/id_node/'.$this->current_id_node.'/disableMainMenu/1');
        }        
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
     * Convert strings so that they can be safely included in html forms
     *
     * @param array $var_array Associative array
     * @param array $fields Field names
     */
    private function convertHtmlSpecialChars( &$var_array, $fields )
    {
        foreach($fields as $f)
        {
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config->getModuleVar('common','charset') );
        }
    }  
    /**
     * Update node data
     *
     * @param int $rank New rank
     */
    private function updateLink()
    {
        $fields = array('id_node'     => (int)$this->current_id_node,
                        'status'      => (int)$this->link_status,
                        'title'       => JapaCommonUtil::stripSlashes((string)$this->link_title),
                        'description' => JapaCommonUtil::stripSlashes((string)$this->link_description),
                        'url'         => JapaCommonUtil::stripSlashes((string)$this->link_url));
    
        $this->model->action('link','updateLink',
                             array('id_link' => (int)$this->id_link,
                                   'fields'  => $fields));    
    }
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function deleteLink( $id_link, $id_node )
    {
        $this->model->action('link','deleteLink',
                             array('id_link' => (int)$id_link,
                                   'id_node' => (int)$id_node));
    }    
    
    /**
     * Redirect to the main user location
     */
    private function redirect( $id_node = 0 )
    {
        // reload the link module
        @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/link/id_node/'.$id_node);
        exit;      
    }  
    /**
     * unlock edited user
     *
     */     
    private function unlockLink()
    {
        $this->model->action('link','lock',
                             array('job'     => 'unlock',
                                   'id_link' => (int)$this->id_link));    
    }    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function addKeyword()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'int');
        // get demanded link data
        $this->model->action('link','addKeyword', 
                             array('id_key'  => (int)$id_key,
                                   'id_link' => (int)$this->id_link));
    }  
    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function getLinkKeywords()
    {
        $this->viewVar['keys'] = array();
        
        $keywords = array();
        
        // get demanded link data
        $this->model->action('link','getKeywordIds', 
                             array('result'  => & $keywords,
                                   'id_link' => (int)$this->id_link));

        foreach($keywords as $key)
        {
            $tmp = array();
            $tmp['id_key'] = $key; 
            
            $keyword = array();
            $this->model->action('keyword','getKeyword', 
                                 array('result' => & $keyword,
                                       'id_key' => (int)$key,
                                       'fields' => array('title','id_key')));          
            $branch = array();
            // get navigation node branch of the current node
            $this->model->action('keyword','getBranch', 
                                 array('result' => & $branch,
                                       'id_key' => (int)$key,
                                       'fields' => array('title','id_key')));                 

            $tmp['branch'] = '';
            
            foreach($branch as $bkey)
            {
                $tmp['branch'] .= '/'.$bkey['title'];
            }
            
            $tmp['branch'] .= '/<strong>'.$keyword['title'].'</strong>';
            
            $this->viewVar['keys'][] = $tmp;
        }
        sort($this->viewVar['keys']);    
    }   
    
    private function deleteLinkKeywords()
    {
        $id_keys = $this->httpRequest->getParameter('id_key', 'request', 'raw');
        if(!empty($id_keys) && is_array($id_keys))
        {
            foreach($id_keys as $id_key)
            {
                // get navigation node branch of the current node
                $this->model->action('link','removeKeyword', 
                                 array('id_key'  => (int)$id_key,
                                       'id_link' => (int)$this->id_link));                 
            
            }
        }
    }    
}

?>