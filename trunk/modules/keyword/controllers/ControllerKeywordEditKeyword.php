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
 * ViewKeywordEditKeyword
 *
 */
 
class ControllerKeywordEditKeyword extends JapaControllerAbstractPage
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
            $this->template        = 'error';
            $this->templateFolder  = 'modules/common/templates/';
            $this->viewVar['error'] = 'You have not the rights to edit a node!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        $this->initVars();

        // is node locked by an other user
        if( TRUE !== $this->lockKeyword() )
        {
            $this->template        = 'error';
            $this->templateFolder  = 'modules/common/templates/';
            $this->viewVar['error'] = 'This node is locked by an other user!';
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

        $gotokey = $this->httpRequest->getParameter('gotokey', 'post', 'alnum');

        // forward to node x without update
        if( !empty($gotokey) )
        {
            $this->unlockKeyword();
            $this->redirect((int)$gotokey);        
        }

        $canceledit = $this->httpRequest->getParameter('canceledit', 'post', 'alnum');
        $this->key_id_parent = $this->httpRequest->getParameter('id_parent', 'post', 'int');
        $this->key_key_id_parent = $this->httpRequest->getParameter('key_id_parent', 'post', 'int');

        // change nothing and switch back
        if(! empty($canceledit) )
        {
            $this->unlockKeyword();
            $this->redirect((int)$this->key_id_parent);        
        }

        $modifykeyworddata = $this->httpRequest->getParameter('modifykeyworddata', 'post', 'alnum');
        
        if( !empty($modifykeyworddata) )
        {      
            $this->updateKeywordData();
        }

        // get whole node tree
        $this->model->action('keyword','getTree', 
                             array('id_key' => 0,
                                   'result' => & $this->viewVar['tree'],
                                   'fields' => array('id_parent','status','id_key','title')));   
        
        // get current node data
        $this->model->action('keyword','getKeyword', 
                             array('result' => & $this->viewVar['key'],
                                   'id_key' => (int)$this->current_id_key,
                                   'error'  => & $this->viewVar['error'],
                                   'fields' => array('title','description',
                                                     'id_parent','status','id_key')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->viewVar['key'], array('title') );        
    
        // get navigation node branch of the current node
        $this->model->action('keyword','getBranch', 
                             array('result' => & $this->viewVar['branch'],
                                   'id_key' => (int)$this->current_id_key,
                                   'error'  => & $this->viewVar['error'],
                                   'fields' => array('title','id_key')));                           
    }  

    private function updateKeywordData()
    {
        $this->key_was_moved  = FALSE;

        $this->key_title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));

        if(empty($this->key_title))
        {
            $this->viewVar['error'] = 'Keyword title is empty!';
            return;
        }
        
        // check if id_parent has changed
        if($this->key_id_parent != $this->key_key_id_parent)
        {
            $id_parent = (string)$this->key_key_id_parent;
            // check if the new id_parent isnt a subnode of the current node
            if(FALSE == $this->isSubKeyword( $id_parent, $this->current_id_key ))
            {
                $this->node_was_moved = TRUE;
            }
            else
            {
                $this->viewVar['error'][] = "Circular error! A new parent keyword cannot be a subkeyword of the current keyword.";
            }
        }
        else
        {
            $id_parent = (int)$this->key_id_parent;
        }

        $delete_key = $this->httpRequest->getParameter('delete_key', 'post', 'alnum');
            
        if(!empty($delete_key))
        {
            $this->unlockKeyword();
            $this->deleteKeyword( $this->current_id_key );
            $this->redirect( $id_parent );
        }           

        // if no error occure update node data
        if(count($this->viewVar['error']) == 0)
        {
            $finishupdate = $this->httpRequest->getParameter('finishupdate', 'post', 'alnum');
            
            // update node data
            $this->updateKeyword();
            if( !empty($finishupdate) )
            {
                $this->unlockKeyword();
                $this->redirect( $id_parent );
            }
        }    
    }
     /**
     * is node locked by an other user?
     *
     */   
    private function lockKeyword()
    {
        return $this->model->action('keyword','lock',
                                    array('job'        => 'lock',
                                          'id_key'     => (int)$this->current_id_key,
                                          'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  
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
        }     

        $this->viewVar['lock_key']    = 'unlock';
        
        // template variables
        //
        // node tree data
        $this->viewVar['tree']   = array();
        // data of the current node
        $this->viewVar['key']    = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();         
        // errors
        $this->viewVar['error']  = array();    
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] <= 40 )
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
    private function updateKeyword()
    { 
        $this->key_status      = $this->httpRequest->getParameter('status', 'post', 'int');
        $this->key_description = trim($this->httpRequest->getParameter('description', 'post', 'raw'));
        $this->old_status      = $this->httpRequest->getParameter('old_status', 'post', 'digits');
        
        $fields = array('id_parent'   => (int)$this->key_key_id_parent,
                        'status'      => (int)$this->key_status,
                        'title'       => JapaCommonUtil::stripSlashes((string)$this->key_title),
                        'description' => JapaCommonUtil::stripSlashes((string)$this->key_description));

        if($this->key_was_moved == TRUE)
        {
            // get id_sector and status of the new parent node
            $new_parent_node_data = array();
            $this->model->action('keyword','getKeyword',
                                  array('id_key' => (int)$this->key_key_id_parent,
                                        'result'  => & $new_parent_node_data,
                                        'fields'  => array('status')));
            
            // only if the new parent node status = 1 (inactive)
            if($new_parent_key_data['status'] == 1)
            {
                $fields['status'] = $new_parent_key_data['status'];
            }
            
            // updates id_sector and status of subnodes
            $this->model->action('keyword','updateSubKeywords',
                                  array('id_key' => (int)$this->current_id_key,
                                        'fields'  => array('status' => (int)$fields['status'])));    
        }
        elseif($this->old_status != $this->key_status)
        {
            // updates status of subnodes
            $this->model->action('keyword','updateSubKeywords',
                                  array('id_key' => (int)$this->current_id_key,
                                        'fields'  => array('status' => (int)$fields['status'])));                                        
        
        }
        
        $this->model->action('keyword','update',
                             array('id_key' => (int)$this->current_id_key,
                                   'fields'  => $fields));    
    }
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function deleteKeyword( $id_key )
    {
        $this->model->action('keyword','delete',
                             array('id_key' => (int)$id_key));
    }    
    /**
     * check on subnode 
     * check if $id_key1 is a subnode of $id_key2
     *
     * @param int $id_key1
     * @param int $id_key2
     * @return bool True or False
     */    
    private function isSubKeyword( $id_key1, $id_key2  )
    {
        if($id_key1 == $id_key2)
        {
            return TRUE;
        }
        return $this->model->action('keyword','isSubKeyword',
                                    array('id_key1' => (int)$id_key1,
                                          'id_key2' => (int)$id_key2));
    }        
    /**
     * Redirect to the main user location
     */
    private function redirect( $id_key = 0 )
    {
        // reload the user module
        $this->router->redirect($this->viewVar['adminWebController'].'/mod/keyword/id_key/'.$id_key);    
    }  
    /**
     * unlock edited user
     *
     */     
    private function unlockKeyword()
    {
        $this->model->action('keyword','lock',
                             array('job'    => 'unlock',
                                   'id_key' => (int)$this->current_id_key));    
    }    
    
}

?>