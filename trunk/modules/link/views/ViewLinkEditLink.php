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
 * ViewLinkEditLink
 *
 */
 
class ViewLinkEditLink extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'editlink';
    
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
            $this->tplVar['error'] = 'You have not the rights to edit a link!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        $this->initVars();

        // is node locked by an other user
        if( TRUE !== $this->lockLink() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'This link is locked by an other user!';
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

        // forward to node x without update
        if(isset($_POST['gotonode']) && ($_POST['gotonode'] != ''))
        {
            $this->unlockLink();
            $this->redirect((int)$_POST['gotonode']);        
        }

        // change nothing and switch back
        if(isset($_POST['canceledit']) && ($_POST['canceledit'] == '1'))
        {
            $this->unlockLink();
            $this->redirect((int)$_POST['id_node']);        
        }
        
        if( isset($_POST['modifylinkdata']) )
        {
            $this->updateLinkData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   
        
        // get demanded link data
        $this->model->action('link','getLink', 
                             array('result'  => & $this->tplVar['link'],
                                   'id_link' => (int)$_REQUEST['id_link'],
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('id_link','title','url',
                                                      'description','status','hits')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['link'], array('title','url') );        

        // get node data of this link
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));  
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','id_node')));                             

        // we need the url vars to open this page by the keyword map window
        if($this->config['link']['use_keywords'] == 1)
        {
            if(isset($_REQUEST['addkey']))
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

        if(empty($_POST['title']))
        {
            $this->tplVar['error'][] = 'Link title is empty!';
        }
        if(empty($_POST['url']))
        {
            $this->tplVar['error'][] = 'Url is empty!';
        }

        if(count($this->tplVar['error']) > 0)
        {
            return FALSE;
        }
        
        if($_POST['link_id_node'] == 0)
        {
            $this->tplVar['id_node']  = (int)$_REQUEST['id_node'];
            $this->current_id_node    = (int)$_REQUEST['id_node'];
            $this->tplVar['error'][] = 'Top node isnt allowed!';
            return FALSE;
        }
        
        // check if id_parent has change
        if($_POST['id_node'] != $_POST['link_id_node'])
        {
            $id_node = (string)$_POST['link_id_node'];
            $this->tplVar['id_node']  = (int)$_POST['link_id_node'];
            $this->current_id_node    = (int)$_POST['link_id_node'];
        }
        else
        {
            $id_node = (int)$_POST['id_node'];
        }
            
        if($_POST['delete_link'] == '1')
        {
            if($this->viewVar['loggedUserRole'] >= 60 )
            {
                return;
            }
            $this->unlockLink();
            $this->deleteLink( $_POST['id_link'], $_POST['id_node'] );
            $this->redirect( $_POST['id_node'] );
        }                

        // if no error occure update node data
        if(count($this->tplVar['error']) == 0)
        {
            // update node data
            $this->updateLink();
            if( isset($_POST['finishupdate']) )
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
                                          'id_link'    => (int)$_REQUEST['id_link'],
                                          'by_id_user' => (int)$this->viewVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
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

        $this->tplVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // node tree data
        $this->tplVar['tree']   = array();
        // data of the current node
        $this->tplVar['node']   = array();
        // data of the branch nodes
        $this->tplVar['branch'] = array();  
        // link data
        $this->tplVar['link']  = array();
       
        // errors
        $this->tplVar['error']  = array();   
        
        // use keywords or not
        $this->tplVar['use_keywords'] = $this->config['link']['use_keywords']; 

        if(isset($_REQUEST['disableMainMenu']))
        {
            $this->tplVar['disableMainMenu']  = "1";  
        }
        else
        {
            $this->tplVar['disableMainMenu']  = FALSE;  
        }
        
        // we need the url vars to open this page by the keyword map window
        if($this->config['link']['use_keywords'] == 1)
        {
            $this->tplVar['opener_url_vars'] = base64_encode('&view=editLink&id_link='.(int)$_REQUEST['id_link'].'&id_node='.$this->current_id_node.'&disableMainMenu=1');
        }        
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->viewVar['loggedUserRole'] < 100 )
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
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config['charset'] );
        }
    }  
    /**
     * Update node data
     *
     * @param int $rank New rank
     */
    private function updateLink()
    {
        $fields = array('id_node'     => (int)$_POST['link_id_node'],
                        'status'      => (int)$_POST['status'],
                        'title'       => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                        'description' => SmartCommonUtil::stripSlashes((string)$_POST['description']),
                        'url'         => SmartCommonUtil::stripSlashes((string)$_POST['url']));
    
        $this->model->action('link','updateLink',
                             array('id_link' => (int)$_REQUEST['id_link'],
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
        @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=link&id_node='.$id_node);
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
                                   'id_link' => (int)$_REQUEST['id_link']));    
    }    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function addKeyword()
    {
        // get demanded link data
        $this->model->action('link','addKeyword', 
                             array('id_key'  => (int)$_REQUEST['id_key'],
                                   'id_link' => (int)$_REQUEST['id_link']));
    }  
    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function getLinkKeywords()
    {
        $this->tplVar['keys'] = array();
        
        $keywords = array();
        
        // get demanded link data
        $this->model->action('link','getKeywordIds', 
                             array('result'  => & $keywords,
                                   'id_link' => (int)$_REQUEST['id_link']));

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
            
            $this->tplVar['keys'][] = $tmp;
        }
        sort($this->tplVar['keys']);    
    }   
    
    private function deleteLinkKeywords()
    {
        if(isset($_POST['id_key']) && is_array($_POST['id_key']))
        {
            foreach($_POST['id_key'] as $id_key)
            {
                // get navigation node branch of the current node
                $this->model->action('link','removeKeyword', 
                                 array('id_key'  => (int)$id_key,
                                       'id_link' => (int)$_REQUEST['id_link']));                 
            
            }
        }
    }    
}

?>