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
 * ControllerMiscEditText
 *
 */
 
class ControllerMiscEditText extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
 
   /**
     * current id_text
     * @var int $current_id_text
     */
    private $current_id_text;    
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
        // init variables for this view
        $this->initVars();
        
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->viewVar['error'] = 'You have not the rights to edit a text!';
            $this->dontPerform = TRUE;
        }
        // check if the demanded text exists
        elseif($this->textExists() == FALSE)
        {
            $this->viewVar['error'] = 'The requested text dosent exists!';
            $this->dontPerform = TRUE;                 
        }
        // is text locked by an other user
        elseif( TRUE !== $this->lockText() )
        {
            $this->viewVar['error'] = 'The requested text is locked by an other user!';
            $this->dontPerform = TRUE;      
        }
        
        if($this->dontPerform == TRUE)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';        
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

        $canceledit = $this->httpRequest->getParameter('canceledit', 'post', 'digits');
        
        // change nothing and switch back
        if(!empty($canceledit) && ($canceledit == '1'))
        {
            $this->unlocktext();
            $this->redirect();        
        }

        $modifytextdata = $this->httpRequest->getParameter('modifytextdata', 'post', 'alnum');

        // update text data
        if( !empty($modifytextdata) )
        {      
            $this->updateTextData();
        }
        
        // get current text data
        $this->model->action('misc','getText', 
                             array('result'  => & $this->viewVar['text'],
                                   'id_text' => (int)$this->current_id_text,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','body','description',
                                                      'media_folder','status',
                                                      'id_text','format')));
        
        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->viewVar['text'], array('title') );        
                                       
        // get user picture thumbnails
        $this->model->action('misc','getAllThumbs',
                             array('result'  => & $this->viewVar['thumb'],
                                   'id_text' => (int)$this->current_id_text,
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
        foreach($this->viewVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['thumb'][$x], array('description') );
            $this->viewVar['thumb'][$x]['description'] = addslashes($this->viewVar['thumb'][$x]['description']);
            $x++;
        }

        // get user files
        $this->model->action('misc','getAllFiles',
                             array('result'  => & $this->viewVar['file'],
                                   'id_text' => (int)$this->current_id_text,
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        foreach($this->viewVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['file'][$x], array('description') );
            $this->viewVar['file'][$x]['description'] = addslashes($this->viewVar['file'][$x]['description']);
            $x++;
        } 
        
        // we need the url vars to open this page by the keyword map window
        if($this->config['misc']['use_keywords'] == 1)
        {
            $addkey = $this->httpRequest->getParameter('addkey', 'request', 'alnum');
            if(!empty($addkey))
            {
                $this->addKeyword();
            }
            $this->getKeywords();
        }        
    }  

    private function updatetextData()
    {
        $use_text_format = FALSE;

        $this->title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        $this->description = trim($this->httpRequest->getParameter('description', 'post', 'raw'));
        $this->body = trim($this->httpRequest->getParameter('body', 'post', 'raw'));
        $this->status = $this->httpRequest->getParameter('status', 'post', 'int');

        $this->uploadlogo = $this->httpRequest->getParameter( 'uploadlogo', 'post', 'alnum' );
        $this->deletelogo = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
        $this->uploadpicture = $this->httpRequest->getParameter( 'uploadpicture', 'post', 'alnum' );
        $this->imageID2del = $this->httpRequest->getParameter( 'imageID2del', 'post', 'raw' );
        $this->imageIDmoveUp = $this->httpRequest->getParameter( 'imageIDmoveUp', 'post', 'digits' );
        $this->imageIDmoveDown = $this->httpRequest->getParameter( 'imageIDmoveDown', 'post', 'digits' );      
        $this->fileIDmoveUp = $this->httpRequest->getParameter( 'fileIDmoveUp', 'post', 'digits' );
        $this->fileIDmoveDown = $this->httpRequest->getParameter( 'fileIDmoveDown', 'post', 'digits' );        
        $this->uploadfile = $this->httpRequest->getParameter( 'uploadfile', 'post', 'alnum' );
        $this->fileID2del = $this->httpRequest->getParameter( 'fileID2del', 'post', 'digits' );
        $this->picid = $this->httpRequest->getParameter( 'picid', 'post', 'raw' );
        $this->fileid = $this->httpRequest->getParameter( 'fileid', 'post', 'raw' );

        if(empty($this->title))
        {
            $this->viewVar['error'] = 'text title is empty!';
            return;
        }

        $delete_text = $this->httpRequest->getParameter('delete_text', 'post', 'digits');
            
        if($delete_text == '1')
        {
            $this->deletetext( $this->current_id_text );
            $this->redirect();
        }                 
        // add picture
        elseif(!empty($this->uploadpicture))
        {   
            $this->model->action('misc','addItem',
                                 array('item'     => 'picture',
                                       'id_text'  => (int)$this->current_id_text,
                                       'postData' => &$picture,
                                       'error'    => & $this->viewVar['error']) ); 
        }
        // delete picture
        elseif(!empty($this->imageID2del))
        {
            $this->model->action('misc','deleteItem',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_pic'  => (int)$this->imageID2del) ); 
        }
        // move image rank up
        elseif(!empty($this->imageIDmoveUp))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_pic'  => (int)!empty($this->node_imageIDmoveUp),
                                       'dir'     => 'up') ); 
        }  
        // move image rank down
        elseif(!empty($this->imageIDmoveDown))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_pic'  => (int)$this->imageIDmoveDown,
                                       'dir'     => 'down') ); 
        } 
        // move file rank up
        elseif(!empty($this->fileIDmoveUp))
        {
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_file' => (int)$this->fileIDmoveUp,
                                       'dir'     => 'up') );                                                 
        }
        // move file rank down
        elseif(!empty($this->fileIDmoveDown))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_file' => (int)$this->fileIDmoveDown,
                                       'dir'     => 'down') );                                                
        } 
        // add file
        elseif(!empty($this->uploadfile))
        {          
            $this->model->action('misc','addItem',
                                 array('item'     => 'file',
                                       'id_text'  => (int)$this->current_id_text,
                                       'postData' => &$ufile,
                                       'error'    => & $this->viewVar['error']) );                          
        }
        // delete file
        elseif(!empty($this->fileID2del))
        {   
            $this->model->action('misc','deleteItem',
                                 array('id_text' => (int)$this->current_id_text,
                                       'id_file' => (int)$this->fileID2del) ); 
        }  
        
        // update picture data if there images
        if(!empty($this->picid))
        {
            $picdesc = $this->httpRequest->getParameter( 'picdesc', 'post', 'raw' );
            $pictitle = $this->httpRequest->getParameter( 'pictitle', 'post', 'raw' );
            $this->model->action( 'misc','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$this->picid,
                                        'fields'  => array('description' => &$picdesc,
                                                           'title'       => &$pictitle)));
        }        

        // update file data if there file attachments
        if(!empty($this->fileid))
        {
            $filedesc = $this->httpRequest->getParameter( 'filedesc', 'post', 'raw' );
            $filetitle = $this->httpRequest->getParameter( 'filetitle', 'post', 'raw' );
            $this->model->action( 'misc','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$this->fileid,
                                        'fields'  => array('description' => &$filedesc,
                                                           'title'       => &$filetitle)));
        }  
        
        // if no error occure update text data
        if(count($this->viewVar['error']) == 0)
        {
            // update text data
            $this->updatetext( $use_text_format );

            $this->deleteKeywords();

            $finishupdate = $this->httpRequest->getParameter( 'finishupdate', 'post', 'alnum' );

            if( !empty($finishupdate) )
            {
                $this->unlocktext();
                $this->redirect();
            }
        }    
    }
     /**
     * is text locked by an other user?
     *
     */   
    private function locktext()
    {
        return $this->model->action('misc','lock',
                                    array('job'        => 'locktext',
                                          'id_text'    => (int)$this->current_id_text,
                                          'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $this->current_id_text = $this->httpRequest->getParameter('id_text', 'request', 'int');
        // fetch the current id_text. If no text the script assums that
        // we are at the top level with id_parent 0
        if( false === $this->current_id_text ) 
        {
            $this->redirect();    
        }
        else
        {
            $this->viewVar['id_text']  = (int)$this->current_id_text;         
        }     

        $format = $this->httpRequest->getParameter('format', 'format', 'digits');

        // set format template var, means how to format textarea content -> editor/wikki ?
        // 1 = text_wikki
        // 2 = tiny_mce
        if($this->config['misc']['force_format'] != 0)
        {
            $this->viewVar['format'] = $this->config['misc']['force_format'];
            $this->viewVar['show_format_switch'] = FALSE;
        }
        elseif(!empty($format))
        {
            if(!preg_match("/(1|2){1}/",$format))
            {
                $this->viewVar['format'] = $this->config['misc']['default_format'];
            }
            $this->viewVar['format'] = $format;
            $this->viewVar['show_format_switch'] = TRUE;
        }
        else
        {
            $this->viewVar['format'] = $this->config['misc']['default_format'];
            $this->viewVar['show_format_switch'] = TRUE;
        }

        $this->viewVar['use_images']    = $this->config['misc']['use_images'];
        $this->viewVar['use_files']     = $this->config['misc']['use_files'];
        $this->viewVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // data of the current text
        $this->viewVar['text']  = array(); 
        // data of thumbs an files attached to this text
        $this->viewVar['thumb'] = array();
        $this->viewVar['file']  = array();        
        // errors
        $this->viewVar['error']  = array();    

        // we need the url vars to open this page by the keyword map window
        if($this->config['misc']['use_keywords'] == 1)
        {
            $this->viewVar['opener_url_vars'] = base64_encode('/cntr/editText/id_text/'.$this->current_id_text.'&disableMainMenu=1');
        }
        $this->viewVar['use_keywords'] = $this->config['misc']['use_keywords'];

        
        $this->dontPerform = FALSE; 
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
            $var_array[$f] = htmlspecialchars ( $var_array[$f], ENT_COMPAT, $this->config['charset'] );
        }
    }  
    /**
     * Update text data
     *
     * @param int $rank New rank
     */
    private function updatetext( $format )
    {
        $fields = array('status'      => (int)$this->status,
                        'title'       => JapaCommonUtil::stripSlashes(strip_tags((string)$this->title)),
                        'description' => JapaCommonUtil::stripSlashes((string)$this->description),
                        'body'        => JapaCommonUtil::stripSlashes((string)$this->body));

        if($format != FALSE)
        {
            $fields['format'] = $format;
        }        
        
        $this->model->action('misc','updateText',
                             array('id_text' => (int)$this->current_id_text,
                                   'fields'  => $fields));    
    }
    /**
     * Get last rank of an given id_parent
     *
     * @param int $id_parent
     */    
    private function deletetext( $id_text )
    {
        $this->model->action('misc','deleteText',
                             array('id_text' => (int)$id_text));
    }    
    
    /**
     * Redirect to the main user location
     */
    private function redirect()
    {
        // reload the user module
        @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/misc');
        exit;      
    }  
    /**
     * unlock edited user
     *
     */     
    private function unlocktext()
    {
        $this->model->action('misc','lock',
                             array('job'     => 'unlocktext',
                                   'id_text' => (int)$this->current_id_text));    
    }    
    /**
     * does a text with a specific id exists
     * @return bool
     */ 
    private function textExists()
    {
        $text  = array();
        $error = array();
        
        // get current text data
        $this->model->action('misc','getText', 
                             array('result'  => & $text,
                                   'id_text' => (int)$this->current_id_text,
                                   'error'   => & $error,
                                   'fields'  => array('id_text')));    
        if($text == NULL)
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * add keyword to the current text
     *
     */      
    private function addKeyword()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'int');
        
        $this->model->action('misc','addKeyword', 
                             array('id_key'  => (int)$id_key,
                                   'id_text' => (int)$this->current_id_text));
    }  
    
    /**
     * get text related keywords
     *
     */      
    private function getKeywords()
    {
        $this->viewVar['keys'] = array();
        
        $keywords = array();
        
        // get text related keywords
        $this->model->action('misc','getKeywordIds', 
                             array('result'  => & $keywords,
                                   'id_text' => (int)$this->current_id_text));

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
        $id_keys = $this->httpRequest->getParameter('id_key', 'request', 'raw');
        
        if(!empty($id_keys) && is_array($id_keys))
        {
            foreach($id_keys as $id_key)
            {
                // remove a keyword relation
                $this->model->action('misc','removeKeyword', 
                                 array('id_key'  => (int)$id_key,
                                       'id_text' => (int)$this->current_id_text));                 
            
            }
        }
    }      
}

?>