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
 * ControllerNavigationEditNode
 *
 */
 
class ControllerNavigationEditNode extends JapaControllerAbstractPage
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
            $this->redirect(0);
        }

        // init variables for this view
        $this->initVars();

        // is node locked by an other user
        if( TRUE !== $this->lockNode() )
        {
            $this->redirect(0);     
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

        $gotonode = $this->httpRequest->getParameter('gotonode', 'post', 'alnum');

        // forward to node x without update
        if($gotonode != '')
        {
            $this->unlocknode();
            $this->redirect((int)$gotonode);        
        }

        $canceledit = $this->httpRequest->getParameter('canceledit', 'post', 'digits');
        $this->node_id_parent = $this->httpRequest->getParameter('id_parent', 'post', 'digits');

        // change nothing and switch back
        if(!empty($canceledit) && ($canceledit == '1'))
        {
            $this->unlocknode();
            $this->redirect((int)$this->node_id_parent);        
        }

        $modifynodedata = $this->httpRequest->getParameter('modifynodedata', 'post', 'alnum');
       
        if( !empty($modifynodedata) )
        {      
            $this->updateNodeData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'    => & $this->viewVar['tree'],
                                   'fields'    => array('id_parent','status','id_node','title')));   
        
        // get current node data
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','body','short_text',
                                                      'id_parent','media_folder','id_controller',
                                                      'status','logo','id_node')));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->viewVar['node'], array('title') );        
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));   
                                   
        // get user picture thumbnails
        $this->model->action('navigation','getAllThumbs',
                             array('result'  => & $this->viewVar['thumb'],
                                   'id_node' => (int)$this->current_id_node,
                                   'order'   => 'rank',
                                   'fields'  => array('id_pic',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'width',
                                                      'height',
                                                      'title',
                                                      'description')) );

        // convert description field to safely include into javascript function call
        $x=0;
        $this->viewVar['node']['thumbdesc'] = array();
        foreach($this->viewVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['thumb'][$x], array('description','title') );
            $x++;
        }

        // get user files
        $this->model->action('navigation','getAllFiles',
                             array('result'  => & $this->viewVar['file'],
                                   'id_node' => (int)$this->current_id_node,
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        $this->viewVar['node']['filedesc'] = array();
        foreach($this->viewVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['file'][$x], array('description','title') );
            $x++;
        }    

        if( $this->viewVar['node']['id_controller'] == 0 )
        {
            // get associated view of the parent node
            if($this->viewVar['node']['id_parent'] != 0)
            {
                $tmp_id_controller = array();
                // get current node data
                $this->model->action('navigation','getNode', 
                                     array('result'  => & $tmp_id_controller,
                                           'id_node' => (int)$this->viewVar['node']['id_parent'],
                                           'error'   => & $this->viewVar['error'],
                                           'fields'  => array('id_controller'))); 
                                           
                $this->viewVar['node']['id_controller'] = $tmp_id_controller['id_controller'];
            }
        }

        // get all available public views
        $this->viewVar['publicControllers'] = array();
        $this->model->action( 'navigation','getNodePublicControllers',
                              array('result' => &$this->viewVar['publicControllers'],
                                    'fields' => array('id_controller','name')) );                              

        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('navigation','use_keywords') == 1)
        {
            $addkey = $this->httpRequest->getParameter('addkey', 'request', 'alnum');
            if(!empty($addkey))
            {
                $this->addKeyword();
            }
            $this->getKeywords();
        }
        
         
        // execute the requested module controller and assign template variable
        // with the result.
        // here we load the requested modul controller output
        // into a view variable
        $this->viewVar['nodeRelatedPublicController'] = '';
        $this->controllerLoader->broadcast($this->viewVar['nodeRelatedPublicController'], 'nodeRelatedPublicController');  
        
        // get url rewrite for this node
        $this->viewVar['url_rewrite'] = array();
        $this->model->action( 'common', 'getUrlRewrite',     
                              array('result'        => & $this->viewVar['url_rewrite'],       
                                    'module'        => 'navigation',
                                    'request_value' => (int)$this->current_id_node) ); 
    }  

    private function updateNodeData()
    {
        $this->node_was_moved  = FALSE;
        $use_text_format       = FALSE;
        
        $this->node_title = $this->httpRequest->getParameter('title', 'post', 'raw');
        $this->node_node_id_parent = $this->httpRequest->getParameter('node_id_parent', 'post', 'digits');
        $this->node_status = $this->httpRequest->getParameter('status', 'post', 'digits');
        $this->node_old_status = $this->httpRequest->getParameter('old_status', 'post', 'digits');
        $this->node_delete_node = $this->httpRequest->getParameter('delete_node', 'post', 'digits');
        $this->node_title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        $this->node_switchformat = $this->httpRequest->getParameter('switchformat', 'post', 'digits');
        $this->node_url_rewrite = trim($this->httpRequest->getParameter('url_rewrite', 'post', 'regex', "/[a-z0-9\.-_]{1,255}/i"));
        $this->node_id_map = $this->httpRequest->getParameter('id_map', 'post', 'digits');
        $this->node_uploadlogo = $this->httpRequest->getParameter( 'uploadlogo', 'post', 'alnum' );
        $this->node_deletelogo = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
        $this->node_uploadpicture = $this->httpRequest->getParameter( 'uploadpicture', 'post', 'alnum' );
        $this->node_imageID2del = $this->httpRequest->getParameter( 'imageID2del', 'post', 'raw' );
        $this->node_imageIDmoveUp = $this->httpRequest->getParameter( 'imageIDmoveUp', 'post', 'digits' );
        $this->node_imageIDmoveDown = $this->httpRequest->getParameter( 'imageIDmoveDown', 'post', 'digits' );      
        $this->node_fileIDmoveUp = $this->httpRequest->getParameter( 'fileIDmoveUp', 'post', 'digits' );
        $this->node_fileIDmoveDown = $this->httpRequest->getParameter( 'fileIDmoveDown', 'post', 'digits' );        
        $this->node_uploadfile = $this->httpRequest->getParameter( 'uploadfile', 'post', 'alnum' );
        $this->node_fileID2del = $this->httpRequest->getParameter( 'fileID2del', 'post', 'digits' );
        $this->node_picid = $this->httpRequest->getParameter( 'picid', 'post', 'raw' );
        $this->node_fileid = $this->httpRequest->getParameter( 'fileid', 'post', 'raw' );

        if(empty($this->node_title))
        {
            $this->viewVar['error'][] = 'Node title is empty!';
            return;
        }
        
        // check if id_parent has changed
        if($this->node_id_parent != $this->node_node_id_parent)
        {
            // only superuser and administrator accounts can move nodes
            if($this->controllerVar['loggedUserRole'] < 40 )
            {
                $new_id_parent = (string)$this->node_node_id_parent;
                
                // check if the new id_parent isnt a subnode of the current node
                if(FALSE == $this->isSubNode( $new_id_parent, $this->current_id_node ))
                {
                    $id_parent = (int)$new_id_parent;
                    
                    $rank = $this->getLastRank( $new_id_parent );
                    if($rank !== FALSE)
                    {
                        $rank++;
                    }
                    else
                    {
                        $rank = 0;
                    }
                    $this->node_was_moved = TRUE;
                }
                else
                {
                    $this->viewVar['error'][] = "Circular error! A new parent node cannot be a subnode of the current node.";
                }
            }
            else
            {
                $this->viewVar['error'][] = "You have no permission to move a node.";
            }            
        }
        else
        {
            $id_parent = (int)$this->node_id_parent;
            $rank = FALSE;
        }

        // check if status has changed
        if($this->node_old_status != $this->node_status)
        {
            // only superuser and administrator accounts can change node status
            if($this->controllerVar['loggedUserRole'] >= 40 )
            {
                $this->viewVar['error'][] = "You have no permission to change node status.";
            }
        }
            
        if($this->node_delete_node == '1')
        {
            // only superuser and administrator accounts can delete nodes
            if($this->controllerVar['loggedUserRole'] < 40 )
            {
                $this->unlockNode();
                $this->deleteNode( $this->current_id_node );
                $this->reorderRank( (int)$this->node_id_parent );
                $this->redirect( $id_parent );
            }
            else
            {
                $this->viewVar['error'][] = "You have no permission to delete a node.";
            }              
        }           
        // switch format of textarea editor
        elseif($this->node_url_rewrite !== false)
        {
            if($this->node_id_map == 0)
            {
                $this->model->action('common','addUrlRewrite',
                                     array( 'module'        => 'navigation',
                                            'request_name'  => (string)$this->node_url_rewrite,
                                            'request_value' => (int)$this->current_id_node) );    
            }  
            else
            {
                $this->model->action('common','updateUrlRewrite',
                                     array( 'id_map'       => (int)$this->node_id_map,
                                            'request_name' => (string)$this->node_url_rewrite) );    
            }   
        }   
        elseif(($this->node_url_rewrite === false) && ($this->node_id_map > 0))
        {
                $this->model->action('common','removeUrlRewrite',
                                     array( 'module' => 'navigation',
                                            'id_map' => (int)$this->node_id_map) );    
        }     
        // upload logo
        elseif(!empty($this->node_uploadlogo))
        {   
            $logo = $this->httpRequest->getParameter( 'logo', 'files', 'raw' );
            $this->model->action('navigation','uploadLogo',
                                 array('id_node'  => (int)$this->current_id_node,
                                       'postData' => & $logo,
                                       'error'    => & $this->viewVar['error']) );                            
        }
        // delete logo 
        elseif(!empty($this->node_deletelogo))
        {   
            $this->model->action('navigation','deleteLogo',
                                 array('id_node' => (int)$this->current_id_node) ); 
        }   
        // add picture
        elseif(!empty($this->node_uploadpicture))
        {   
            $picture = $this->httpRequest->getParameter( 'picture', 'files', 'raw' );
            $this->model->action('navigation','addItem',
                                 array('item'     => 'picture',
                                       'id_node'  => (int)$this->current_id_node,
                                       'postData' => &$picture,
                                       'error'    => & $this->viewVar['error']) ); 
        }
        // delete picture
        elseif(!empty($this->node_imageID2del))
        {
            $this->model->action('navigation','deleteItem',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_pic'  => (int)$this->node_imageID2del) ); 
        }
        // move image rank up
        elseif(!empty($this->node_imageIDmoveUp))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_pic'  => (int)$this->node_imageIDmoveUp,
                                       'dir'     => 'up') ); 
        }  
        // move image rank down
        elseif(!empty($this->node_imageIDmoveDown))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_pic'  => (int)$this->node_imageIDmoveDown,
                                       'dir'     => 'down') ); 
        } 
        // move file rank up
        elseif(!empty($this->node_fileIDmoveUp))
        {
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_file' => (int)$this->node_fileIDmoveUp,
                                       'dir'     => 'up') );                                                 
        }
        // move file rank down
        elseif(!empty($this->node_fileIDmoveDown))
        {   
            $this->model->action('navigation','moveItemRank',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_file' => (int)$this->node_fileIDmoveDown,
                                       'dir'     => 'down') );                                                
        } 
        // add file
        elseif(!empty($this->node_uploadfile))
        {          
            $ufile = $this->httpRequest->getParameter( 'ufile', 'files', 'raw' ); 
            $this->model->action('navigation','addItem',
                                 array('item'     => 'file',
                                       'id_node'  => (int)$this->current_id_node,
                                       'postData' => &$ufile,
                                       'error'    => & $this->viewVar['error']) );                          
        }
        // delete file
        elseif(!empty($this->node_fileID2del))
        {   
            $this->model->action('navigation','deleteItem',
                                 array('id_node' => (int)$this->current_id_node,
                                       'id_file' => (int)$this->node_fileID2del) ); 
        }  
        
        // update picture data if there images
        if(!empty($this->node_picid))
        {
            $picdesc = $this->httpRequest->getParameter( 'picdesc', 'post', 'raw' );
            $pictitle = $this->httpRequest->getParameter( 'pictitle', 'post', 'raw' );
            $this->model->action( 'navigation','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$this->node_picid,
                                        'fields'  => array('description' => $this->stripSlashesArray($picdesc),
                                                           'title'       => $this->stripSlashesArray($pictitle))));
        }        

        // update file data if there file attachments
        if(!empty($this->node_fileid))
        {
            $filedesc = $this->httpRequest->getParameter( 'filedesc', 'post', 'raw' );
            $filetitle = $this->httpRequest->getParameter( 'filetitle', 'post', 'raw' );
            $this->model->action( 'navigation','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$this->node_fileid,
                                        'fields'  => array('description' => $this->stripSlashesArray($filedesc),
                                                           'title'       => $this->stripSlashesArray($filetitle))));
        }  
        
        // Remove selected keyword relations
        $this->deleteKeywords();
        
        // if no error occure update node data
        if(count($this->viewVar['error']) == 0)
        {
            // update node data
            $this->updateNode( $rank, $use_text_format );
            if($this->node_was_moved == TRUE)
            {
                $this->reorderRank( (int)$this->node_id_parent );
            }
            $finishupdate = $this->httpRequest->getParameter( 'finishupdate', 'post', 'alnum' );
            if( !empty($finishupdate) )
            {
                $this->unlockNode();
                $this->redirect( $this->node_id_parent );
            }
        }    
    }
     /**
     * is node locked by an other user?
     *
     */   
    private function lockNode()
    {
        return $this->model->action('navigation','lock',
                                    array('job'        => 'lock',
                                          'id_node'    => (int)$this->current_id_node,
                                          'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $id_node = $this->httpRequest->getParameter('id_node', 'request', 'digits');
        $format = $this->httpRequest->getParameter('format', 'request', 'digits');
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $id_node ) 
        {
            $this->viewVar['id_node']  = 0;
            $this->current_id_node     = 0;      
        }
        else
        {
            $this->viewVar['id_node']  = (int)$id_node;
            $this->current_id_node     = (int)$id_node;          
        }     

        $this->viewVar['use_url_rewrite'] = $this->config->getModuleVar('navigation','use_url_rewrite');
        $this->viewVar['use_logo']      = $this->config->getModuleVar('navigation','use_logo');
        $this->viewVar['use_images']    = $this->config->getModuleVar('navigation','use_images');
        $this->viewVar['use_files']     = $this->config->getModuleVar('navigation','use_files');
        $this->viewVar['use_shorttext'] = $this->config->getModuleVar('navigation','use_short_text');        
        $this->viewVar['use_body']      = $this->config->getModuleVar('navigation','use_body');
        $this->viewVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // node tree data
        $this->viewVar['tree']   = array();
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // data of thumbs an files attached to this node
        $this->viewVar['thumb']  = array();
        $this->viewVar['file']   = array();        
        // errors
        $this->viewVar['error']  = array();    

        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('navigation','use_keywords') == 1)
        {
            $this->viewVar['opener_url_vars'] = base64_encode('/cntr/editNode/id_node/'.$this->current_id_node.'/disableMainMenu/1');
        }
        $this->viewVar['use_keywords'] = $this->config->getModuleVar('navigation','use_keywords');
    }
     /**
     * has the logged the rights to modify?
     * at least edit (40) rights are required
     *
     */      
    private function allowModify()
    {      
        $info = $this->model->getModuleInfo('navigation');
        if($this->controllerVar['loggedUserRole'] <= $info['perm'] )
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
    private function updateNode( $rank, $format )
    {
        $fields = array();
        
        if($this->config->getModuleVar('navigation','use_short_text') == 1)
        {
            $short_text = $this->httpRequest->getParameter('short_text', 'post', 'raw');
            $fields['short_text'] = JapaCommonUtil::stripSlashes((string)$short_text);
        }
        
        if($this->config->getModuleVar('navigation','use_body') == 1)
        {
            $body = $this->httpRequest->getParameter('body', 'post', 'raw');
            $fields['body'] = JapaCommonUtil::stripSlashes((string)$body);
        }
        
        $node_id_parent = $this->httpRequest->getParameter('node_id_parent', 'post', 'digits');
        $status = $this->httpRequest->getParameter('status', 'post', 'digits');
        $old_status = $this->httpRequest->getParameter('old_status', 'post', 'digits');
        $title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        
        $fields['id_parent']  = (int)$node_id_parent;
        $fields['status']     = (int)$status;
        $fields['title']      = JapaCommonUtil::stripSlashes((string)$title);

        if($this->node_was_moved == TRUE)
        {
            $sub_node_fields = array();
            // if new id_parent != 0 get the info about new parent node
            if($node_id_parent != 0)
            {        
                // get id_sector, id_controller and status of the new parent node
                $new_parent_node_data = array();
                $this->model->action('navigation','getNode',
                                      array('id_node' => (int)$node_id_parent,
                                            'result'  => & $new_parent_node_data,
                                            'fields'  => array('status','id_sector')));
              
                $sub_node_fields['status']    = (int)$new_parent_node_data['status'];
                $sub_node_fields['id_sector'] = (int)$new_parent_node_data['id_sector'];
            }
            else
            {
                // set this node as sector
                $sub_node_fields['id_sector'] = (int)$this->current_id_node;
            }
            
            // updates id_sector and status of subnodes
            $this->model->action('navigation','updateSubNodes',
                                  array('id_node' => (int)$this->current_id_node,
                                        'fields'  => $sub_node_fields));  
            
            $fields = array_merge($fields,$sub_node_fields); 
        }
        elseif($old_status != $status)
        {
            // updates status of subnodes
            $this->model->action('navigation','updateSubNodes',
                                  array('id_node' => (int)$this->current_id_node,
                                        'fields'  => array('status' => (int)$fields['status'])));                                        
        
        }

        $controllerssubnodes = $this->httpRequest->getParameter('controllerssubnodes', 'post', 'alnum');
        $id_controller = $this->httpRequest->getParameter('id_controller', 'post', 'digits');   
            
        // set id_controller of subnodes
        if(false !== $controllerssubnodes)
        {
            // updates status of subnodes
            $this->model->action('navigation','updateSubNodes',
                                  array('id_node' => (int)$this->current_id_node,
                                        'fields'  => array('id_controller' => (int)$id_controller)));        
        }
                        
        if($rank != FALSE)
        {
            $fields['rank'] = $rank;
        }

        // only administrators can assign a node related view
        if($this->controllerVar['loggedUserRole'] <= 20)
        {
            $fields['id_controller'] = (int)$id_controller;
        }
        
        if($format != FALSE)
        {
            $fields['format'] = $format;
        }        
        
        $this->model->action('navigation','updateNode',
                             array('id_node' => (int)$this->current_id_node,
                                   'fields'  => $fields));    
    }
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function deleteNode( $id_node )
    {
        $this->model->action('navigation','deleteNode',
                             array('id_node' => (int)$id_node));
    }    
    /**
     * check on subnode 
     * check if $id_node1 is a subnode of $id_node2
     *
     * @param int $id_node1
     * @param int $id_node2
     * @return bool True or False
     */    
    private function isSubNode( $id_node1, $id_node2  )
    {
        if($id_node1 == $id_node2)
        {
            return TRUE;
        }
        return $this->model->action('navigation','isSubNode',
                                    array('id_node1' => (int)$id_node1,
                                          'id_node2' => (int)$id_node2));
    }        
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function getLastRank( $id_parent )
    {
        $rank = 0;
        $this->model->action('navigation','getLastRank',
                             array('id_parent' => (int)$id_parent,
                                   'result'    => &$rank ));
        return $rank;
    }
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_parent
     */      
    private function reorderRank( $id_parent )
    {
        $this->model->action('navigation','reorderRank',
                             array('id_parent' => (int)$id_parent));
    }  
    /**
     * Redirect to the main user location
     */
    private function redirect( $id_node = 0 )
    {
        // reload the user module 
        $this->router->redirect($this->viewVar['adminWebController'].'/mod/navigation/id_node/'.$id_node);   
    }  
    /**
     * unlock edited user
     *
     */     
    private function unlockNode()
    {
        $this->model->action('navigation','lock',
                             array('job'     => 'unlock',
                                   'id_node' => (int)$this->current_id_node));    
    }    
    /**
     * add keyword to the current node
     *
     */      
    private function addKeyword()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'digits');
        
        $this->model->action('navigation','addKeyword', 
                             array('id_key'  => (int)$id_key,
                                   'id_node' => (int)$this->current_id_node));
    }  

    /**
     * strip slashes from form fields
     *
     * @param array $var_array Associative array
     */
    private function stripSlashesArray( &$var_array)
    {
        $tmp_array = array();
        foreach($var_array as $f)
        {
            //$tmp_array[] = preg_replace("/\"/","'",JapaCommonUtil::stripSlashes( $f ));
            $tmp_array[] = JapaCommonUtil::stripSlashes( $f );
        }

        return $tmp_array;
    } 
    
    /**
     * get node related keywords
     *
     */      
    private function getKeywords()
    {
        $this->viewVar['keys'] = array();
        
        $keywords = array();
        
        // get node related keywords
        $this->model->action('navigation','getKeywordIds', 
                             array('result'  => & $keywords,
                                   'id_node' => (int)$this->current_id_node));

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
            // get keywords branches
            $this->model->action('keyword','getBranch', 
                                 array('result'  => & $branch,
                                       'id_key' => (int)$key,
                                       'fields'  => array('title','id_key')));                 

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
    /**
     * remove keyword relations
     *
     */      
    private function deleteKeywords()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'raw');
        
        if((false !== $id_key) && is_array($id_key))
        {
            foreach($id_key as $id)
            {
                // remove a keyword relation
                $this->model->action('navigation','removeKeyword', 
                                 array('id_key'  => (int)$id,
                                       'id_node' => (int)$this->current_id_node));                 
            
            }
        }
    }    
}

?>