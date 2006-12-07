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
 * ControllerArticleModArticle
 *
 */
 
class ControllerArticleModArticle extends JapaControllerAbstractPage
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
     * current id_article
     * @var int $current_id_article
     */
    private $current_id_article;   
    
   /**
     * execute the perform methode
     * @var bool $dontPerform
     */
    private $dontPerform = FALSE;       
    
   /**
     * user log message for this view
     * @var string $logMessage
     */    
    private $logMessage = '';
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        $this->current_id_article = $this->httpRequest->getParameter('id_article', 'request', 'digits');
        $this->current_id_node    = $this->httpRequest->getParameter('id_node', 'request', 'digits');
        
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->redirect();
        }

        // init variables for this view
        if(FALSE == $this->initVars())
        {
            $this->redirect();        
        }
        
        // is article locked by an other user
        $is_locked = $this->model->action('article','lock',
                                          array('job'        => 'is_locked',
                                                'id_article' => (int)$this->current_id_article,
                                                'by_id_user' => (int)$this->controllerVar['loggedUserId']));

        if( (TRUE !== $is_locked) && (FALSE !== $is_locked) )
        {
            $this->redirect();     
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

        $back = $this->httpRequest->getParameter('back', 'post', 'alpha');

        // change nothing and switch back
        if($back == 'Back')
        {
            $this->redirect();        
        }
        
        $modifyarticledata = $this->httpRequest->getParameter('modifyarticledata', 'post', 'alpha');
        
        if( !empty($modifyarticledata) )
        {      
            $this->updateArticleData();
        }

        // article fields to get
        $articleFields = array('id_article','title','body','media_folder');
        // add fields depended on configuration settings
        $this->addGetArticleFields( $articleFields );
        
        // get demanded article data
        $this->model->action('article','getArticle', 
                             array('result'     => & $this->viewVar['article'],
                                   'id_article' => (int)$this->current_id_article,
                                   'error'      => & $this->viewVar['error'],
                                   'fields'     => $articleFields));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->viewVar['article'], 
                                        $articleFields );                            

        // get user picture thumbnails
        $this->model->action('article','getAllThumbs',
                             array('result'     => & $this->viewVar['thumb'],
                                   'id_article' => array((int)$this->current_id_article),
                                   'order'      => array('rank','ASC'),
                                   'status'     => array('>=',0),
                                   'fields'     => array('id_pic','file',
                                                         'size','mime',
                                                         'width','height',
                                                         'title','description')) );

        // convert description field to safely include into javascript function call
        $x=0;
        foreach($this->viewVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['thumb'][$x], array('description','title') );
            //$this->viewVar['thumb'][$x]['description'] = $this->viewVar['thumb'][$x]['description'];
            //$this->viewVar['thumb'][$x]['title'] = $this->viewVar['thumb'][$x]['title'];
            $x++;
        }

        // get user files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->viewVar['file'],
                                   'id_article' => array((int)$this->current_id_article),
                                   'status'     => array('>=',0),
                                   'order'      => array('rank','ASC'),
                                   'fields'     => array('id_file','file',
                                                         'size','mime',
                                                         'title','description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        foreach($this->viewVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->viewVar['file'][$x], array('description','title') );
            //$this->viewVar['file'][$x]['description'] = $this->viewVar['file'][$x]['description'];
            $x++;
        }   
    }  

    private function updateArticleData()
    {
        $noRedirect = FALSE;

        $this->article_body  = trim($this->httpRequest->getParameter('body', 'post', 'raw'));
        $this->article_old_status = $this->httpRequest->getParameter('old_status', 'post', 'digits');
        $this->article_delete_node = $this->httpRequest->getParameter('delete_node', 'post', 'digits');
        $this->article_title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        $this->article_uploadlogo = $this->httpRequest->getParameter( 'uploadlogo', 'post', 'alnum' );
        $this->article_deletelogo = $this->httpRequest->getParameter( 'deletelogo', 'post', 'alnum' );
        $this->article_uploadpicture = $this->httpRequest->getParameter( 'uploadpicture', 'post', 'alnum' );
        $this->article_imageID2del = $this->httpRequest->getParameter( 'imageID2del', 'post', 'raw' );
        $this->article_imageIDmoveUp = $this->httpRequest->getParameter( 'imageIDmoveUp', 'post', 'digits' );
        $this->article_imageIDmoveDown = $this->httpRequest->getParameter( 'imageIDmoveDown', 'post', 'digits' );      
        $this->article_fileIDmoveUp = $this->httpRequest->getParameter( 'fileIDmoveUp', 'post', 'digits' );
        $this->article_fileIDmoveDown = $this->httpRequest->getParameter( 'fileIDmoveDown', 'post', 'digits' );        
        $this->article_uploadfile = $this->httpRequest->getParameter( 'uploadfile', 'post', 'alnum' );
        $this->article_fileID2del = $this->httpRequest->getParameter( 'fileID2del', 'post', 'digits' );
        $this->article_picid = $this->httpRequest->getParameter( 'picid', 'post', 'raw' );
        $this->article_fileid = $this->httpRequest->getParameter( 'fileid', 'post', 'raw' );
       
        if(empty($this->article_title))
        {
            $this->viewVar['error'][] = 'Article title is empty!';
            return FALSE;
        }

        if(!empty($this->article_uploadpicture))
        {   
            $picture = $this->httpRequest->getParameter( 'picture', 'files', 'raw' );
            $this->model->action('article','addItem',
                                 array('item'        => 'picture',
                                       'error'       => & $this->viewVar['error'],
                                       'id_article'  => (int)$this->current_id_article,
                                       'postData'    => &$picture,
                                       'error'       => & $this->viewVar['error']) ); 
                                       
            $this->addLogMessage( "Upload picture" );
            $noRedirect = TRUE;
        }
        // upload logo
        elseif(!empty($this->article_uploadlogo))
        {   
            $logo = $this->httpRequest->getParameter( 'logo', 'files', 'raw' );
            $this->model->action('article','uploadLogo',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error'],
                                       'postData'   => & $logo,) );  
                                       
            $this->addLogMessage( "Upload logo" );
            $noRedirect = TRUE;
        }
        // delete logo
        elseif(!empty($this->article_deletelogo))
        {   
            $this->model->action('article','deleteLogo',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error']) ); 
                                       
            $this->addLogMessage( "Delete logo" );
            $noRedirect = TRUE;
        }           
        // delete picture
        elseif(!empty($this->article_imageID2del))
        {
            $this->model->action('article','deleteItem',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error'],
                                       'id_pic'     => (int)$this->article_imageID2del) ); 
                                       
            $this->addLogMessage( "Delete images" );
            $noRedirect = TRUE;
        }
        // move image rank up
        elseif(!empty($this->article_imageIDmoveUp))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error'],
                                       'id_pic'     => (int)$this->article_imageIDmoveUp,
                                       'dir'        => 'up') ); 
                                       
            $this->addLogMessage( "Move image rank up" );
            $noRedirect = TRUE;
        }  
        // move image rank down
        elseif(!empty($this->article_imageIDmoveDown))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_pic'     => (int)$this->article_imageIDmoveDown,
                                       'error'      => & $this->viewVar['error'],
                                       'dir'        => 'down') ); 
                                       
            $this->addLogMessage( "Move image rank down" );
            $noRedirect = TRUE;
        } 
        // move file rank up
        elseif(!empty($this->article_fileIDmoveUp))
        {
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_file'    => (int)$this->article_fileIDmoveUp,
                                       'error'      => & $this->viewVar['error'],
                                       'dir'        => 'up') );  
                                       
            $this->addLogMessage( "Move file rank up" );
            $noRedirect = TRUE;
        }
        // move file rank down
        elseif(!empty($this->article_imageIDmoveDown))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_file'    => (int)$this->article_imageIDmoveDown,
                                       'error'      => & $this->viewVar['error'],
                                       'dir'        => 'down') );  
                                       
            $this->addLogMessage( "Move file rank down" );
            $noRedirect = TRUE;
        } 
        // add file
        elseif(!empty($this->article_uploadfile))
        {          
            $ufile = $this->httpRequest->getParameter( 'ufile', 'files', 'raw' ); 
            $this->model->action('article','addItem',
                                 array('item'        => 'file',
                                       'id_article'  => (int)$this->current_id_article,
                                       'postData'    => &$ufile,
                                       'error'       => & $this->viewVar['error']) );
                                       
            $this->addLogMessage( "Upload file" );
            $noRedirect = TRUE;
        }
        // delete file
        elseif(!empty($this->article_fileID2del))
        {   
            $this->model->action('article','deleteItem',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error'],
                                       'id_file'    => (int)$this->article_fileID2del) );
                                       
            $this->addLogMessage( "Delete files" );
            $noRedirect = TRUE;
        }  
        
        // update picture data if there images
        if(!empty($this->article_picid) && is_array($this->article_picid))
        {
            $picdesc  = $this->httpRequest->getParameter( 'picdesc', 'post', 'raw' );
            $pictitle = $this->httpRequest->getParameter( 'pictitle', 'post', 'raw' );
            
            $this->model->action( 'article','updateItem',
                                  array('item'    => 'pic',
                                        'error'   => & $this->viewVar['error'],
                                        'ids'     => &$this->article_picid,
                                        'fields'  => array('description' => $this->stripSlashesArray($picdesc),
                                                           'title'       => $this->stripSlashesArray($pictitle))));

            $this->addLogMessage( "Update pictures data" );
            $noRedirect = TRUE;
        }        

        // update file data if there file attachments
        if(!empty($this->article_fileid) && is_array($this->article_fileid))
        {
            $filedesc  = $this->httpRequest->getParameter( 'filedesc', 'post', 'raw' );
            $filetitle = $this->httpRequest->getParameter( 'filetitle', 'post', 'raw' );
            
            $this->model->action( 'article','updateItem',
                                  array('item'    => 'file',
                                        'error'   => & $this->viewVar['error'],
                                        'ids'     => &$this->article_fileid,
                                        'fields'  => array('description' => $this->stripSlashesArray($filedesc),
                                                           'title'       => $this->stripSlashesArray($filetitle))));
            
            $this->addLogMessage( "Update files data" );
            $noRedirect = TRUE;
        }  

        // if no error occure update text data
        if(count($this->viewVar['error']) == 0)
        {
            $articleFields = array('title'  => JapaCommonUtil::stripSlashes((string)$this->article_title),
                                   'body'   => JapaCommonUtil::stripSlashes((string)$this->article_body));

            // add fields depended on configuration settings
            $this->addSetArticleFields( $articleFields );         
    
            $this->model->action('article','updateArticle',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->viewVar['error'],
                                       'fields'     => $articleFields));   
                                       
            $this->addLogMessage( "Update article data fields" );
            $this->addLogEvent( 3 );

            $finishupdate = $this->httpRequest->getParameter( 'finishupdate', 'post', 'alnum' );
            if( !empty($finishupdate) )
            {
                $this->redirect(); 
            }
        }
    }  
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // get node Id of the demanded article
        if( false === $this->current_id_node )
        {
                return FALSE;
        } 
        
        // template variables
        //
        // article data
        $this->viewVar['id_article'] = $this->current_id_article;
        $this->viewVar['id_node']    = $this->current_id_node;
        
        $this->viewVar['article']  = array();
        $this->viewVar['file']     = array();
        $this->viewVar['thumb']    = array();
       
        // errors
        $this->viewVar['error']  = array(); 

        // assign view config vars
        $this->viewVar['use_overtitle']   = $this->config->getModuleVar('article', 'use_overtitle');
        $this->viewVar['use_subtitle']    = $this->config->getModuleVar('article', 'use_subtitle');
        $this->viewVar['use_description'] = $this->config->getModuleVar('article', 'use_description');
        $this->viewVar['use_header']      = $this->config->getModuleVar('article', 'use_header');
        $this->viewVar['use_ps']          = $this->config->getModuleVar('article', 'use_ps');
        $this->viewVar['use_files']       = $this->config->getModuleVar('article', 'use_files');
        $this->viewVar['use_logo']        = $this->config->getModuleVar('article', 'use_logo');
        $this->viewVar['use_images']      = $this->config->getModuleVar('article', 'use_images');
        
        return true;
    }
    
     /**
     * has the logged user the rights to modify article data?
     * at least 'edit' (40) rights are required
     * or author (60) is assigned to this article
     *
     * @return bool
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] < 60 )
        {
            return $this->allowModify = true;
        }
        elseif(($this->controllerVar['loggedUserRole'] >= 60) &&
               ($this->controllerVar['loggedUserRole'] < 100))
        {
            return $this->allowModify = $this->model->action('article','checkUserRights',
                                        array('id_article' => (int)$this->current_id_article,
                                              'id_user'    => (int)$this->controllerVar['loggedUserId']));
        }
        
        return $this->allowModify = false;
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
     * strip slashes from form fields
     *
     * @param array $var_array Associative array
     */
    private function stripSlashesArray( &$var_array)
    {
        $tmp_array = array();
        foreach($var_array as $f)
        {
            $tmp_array[] = preg_replace("/\"/","'",JapaCommonUtil::stripSlashes( $f ));
        }

        return $tmp_array;
    } 
    
    /**
     * Redirect to the editArticle view
     */
    private function redirect()
    { 
        $this->router->redirect( $this->controllerVar['adminWebController'].'/mod/article/cntr/editArticle/disableMainMenu/1/id_article/'.$this->current_id_article.'/id_node/'.$this->current_id_node );    
    }  
    
    /**
     * add article fields to get depended on the configuration settings
     *
     */     
    private function addGetArticleFields( & $articleFields )
    {
        if($this->config->getModuleVar('article','use_overtitle') == 1)
        {
            array_push($articleFields, 'overtitle');
        }
        if($this->config->getModuleVar('article','use_subtitle') == 1)
        {
            array_push($articleFields, 'subtitle');
        }   
        if($this->config->getModuleVar('article','use_description') == 1)
        {
            array_push($articleFields, 'description');
        }
        if($this->config->getModuleVar('article','use_header') == 1)
        {
            array_push($articleFields, 'header');
        }   
        if($this->config->getModuleVar('article','use_ps') == 1)
        {
            array_push($articleFields, 'ps');
        }
        if($this->config->getModuleVar('article','use_logo') == 1)
        {
            array_push($articleFields, 'logo');
        }        
    }

    /**
     * set article field values depended on the configuration settings
     *
     */      
    private function addSetArticleFields( & $articleFields )
    {
        if($this->config->getModuleVar('article','use_overtitle') == 1)
        {
            $overtitle = trim($this->httpRequest->getParameter('overtitle', 'post', 'raw'));
            $articleFields['overtitle'] = JapaCommonUtil::stripSlashes((string)$overtitle);
        }
        if($this->config->getModuleVar('article','use_subtitle') == 1)
        {
            $subtitle  = trim($this->httpRequest->getParameter('subtitle', 'post', 'raw'));
            $articleFields['subtitle'] = JapaCommonUtil::stripSlashes((string)$subtitle);
        }   
        if($this->config->getModuleVar('article','use_description') == 1)
        {
            $description  = trim($this->httpRequest->getParameter('description', 'post', 'raw'));
            $articleFields['description'] = JapaCommonUtil::stripSlashes((string)$description);
        }
        if($this->config->getModuleVar('article','use_header') == 1)
        {
            $header  = trim($this->httpRequest->getParameter('header', 'post', 'raw'));
            $articleFields['header'] = JapaCommonUtil::stripSlashes((string)$header);
        }   
        if($this->config->getModuleVar('article','use_ps') == 1)
        {
            $ps  = trim($this->httpRequest->getParameter('ps', 'post', 'raw'));
            $articleFields['ps'] = JapaCommonUtil::stripSlashes((string)$ps);
        }               
    }  
    
    /**
     * log events of this view
     *
     * for $type values see: /modules/user/actions/ActionUserLogAddEvent.php
     *
     * @param int $type 
     */     
    private function addLogEvent( $type )
    {
        // dont log
        if($this->config->getModuleVar('user','use_log') == 0)
        {
            return;
        }
        
        $this->model->action('user','logAddEvent',
                             array('type'    => $type,
                                   'id_item' => (int)$this->current_id_article,
                                   'module'  => 'article',
                                   'view'    => 'modArticle',
                                   'message' => $this->logMessage ));
    }
    /**
     * add log message string
     *
     *
     * @param string $message 
     */  
    private function addLogMessage( $message = '' )
    {
        // dont log
        if($this->config->getModuleVar('user','use_log') == 0)
        {
            return;
        }
        $this->logMessage .= $message."\n";
    }
}

?>