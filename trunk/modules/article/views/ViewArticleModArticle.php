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
 * ViewArticleModArticle
 *
 */
 
class ViewArticleModArticle extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'modarticle';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/article/templates/';
    
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
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to edit a link!';
            $this->dontPerform = TRUE;
        }

        // init variables for this view
        if(FALSE == $this->initVars())
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'Fatal error: No/Wrong "id_node" or "id_article" var defined';
            $this->dontPerform = TRUE;          
        }
        
        // is article locked by an other user
        $is_locked = $this->model->action('article','lock',
                                          array('job'        => 'is_locked',
                                                'id_article' => (int)$this->current_id_article,
                                                'by_id_user' => (int)$this->viewVar['loggedUserId']));

        if( (TRUE !== $is_locked) && (FALSE !== $is_locked) )
        {
            $this->template        = 'error';
            $this->templateFolder  = 'modules/common/templates/';
            $this->tplVar['error'] = 'This article is locked by an other user!';
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

        // change nothing and switch back
        if(isset($_POST['back']) && ($_POST['back'] == 'Back'))
        {
            $this->redirect();        
        }
        
        if( isset($_POST['modifyarticledata']) )
        {      
            $this->updateArticleData();
        }

        // article fields to get
        $articleFields = array('id_article','title','body','media_folder');
        // add fields depended on configuration settings
        $this->addGetArticleFields( $articleFields );
        
        // get demanded article data
        $this->model->action('article','getArticle', 
                             array('result'     => & $this->tplVar['article'],
                                   'id_article' => (int)$this->current_id_article,
                                   'error'      => & $this->tplVar['error'],
                                   'fields'     => $articleFields));

        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['article'], 
                                        $articleFields );                            

        // get user picture thumbnails
        $this->model->action('article','getAllThumbs',
                             array('result'     => & $this->tplVar['thumb'],
                                   'id_article' => array((int)$this->current_id_article),
                                   'order'      => array('rank','ASC'),
                                   'status'     => array('>=',0),
                                   'fields'     => array('id_pic','file',
                                                         'size','mime',
                                                         'width','height',
                                                         'title','description')) );

        // convert description field to safely include into javascript function call
        $x=0;
        foreach($this->tplVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['thumb'][$x], array('description','title') );
            //$this->tplVar['thumb'][$x]['description'] = $this->tplVar['thumb'][$x]['description'];
            //$this->tplVar['thumb'][$x]['title'] = $this->tplVar['thumb'][$x]['title'];
            $x++;
        }

        // get user files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->tplVar['file'],
                                   'id_article' => array((int)$this->current_id_article),
                                   'status'     => array('>=',0),
                                   'order'      => array('rank','ASC'),
                                   'fields'     => array('id_file','file',
                                                         'size','mime',
                                                         'title','description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        foreach($this->tplVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['file'][$x], array('description','title') );
            //$this->tplVar['file'][$x]['description'] = $this->tplVar['file'][$x]['description'];
            $x++;
        }   
    }  

    private function updateArticleData()
    {
        $noRedirect = FALSE;
        
        if(empty($_POST['title']))
        {
            $this->tplVar['error'][] = 'Article title is empty!';
            return FALSE;
        }

        if(isset($_POST['uploadpicture']) && !empty($_POST['uploadpicture']))
        {   
            $this->model->action('article','addItem',
                                 array('item'        => 'picture',
                                       'error'       => & $this->tplVar['error'],
                                       'id_article'  => (int)$this->current_id_article,
                                       'postName'    => 'picture',
                                       'error'       => & $this->tplVar['error']) ); 
                                       
            $this->addLogMessage( "Upload picture" );
            $noRedirect = TRUE;
        }
        // upload logo
        elseif(isset($_POST['uploadlogo']) && !empty($_POST['uploadlogo']))
        {   
            $this->model->action('article','uploadLogo',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error'],
                                       'postName'   => 'logo') );  
                                       
            $this->addLogMessage( "Upload logo" );
            $noRedirect = TRUE;
        }
        // delete logo
        elseif(isset($_POST['deletelogo']) && !empty($_POST['deletelogo']))
        {   
            $this->model->action('article','deleteLogo',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error']) ); 
                                       
            $this->addLogMessage( "Delete logo" );
            $noRedirect = TRUE;
        }           
        // delete picture
        elseif(isset($_POST['imageID2del']) && !empty($_POST['imageID2del']))
        {
            $this->model->action('article','deleteItem',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error'],
                                       'id_pic'     => (int)$_POST['imageID2del']) ); 
                                       
            $this->addLogMessage( "Delete images" );
            $noRedirect = TRUE;
        }
        // move image rank up
        elseif(isset($_POST['imageIDmoveUp']) && !empty($_POST['imageIDmoveUp']))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error'],
                                       'id_pic'     => (int)$_POST['imageIDmoveUp'],
                                       'dir'        => 'up') ); 
                                       
            $this->addLogMessage( "Move image rank up" );
            $noRedirect = TRUE;
        }  
        // move image rank down
        elseif(isset($_POST['imageIDmoveDown']) && !empty($_POST['imageIDmoveDown']))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_pic'     => (int)$_POST['imageIDmoveDown'],
                                       'error'      => & $this->tplVar['error'],
                                       'dir'        => 'down') ); 
                                       
            $this->addLogMessage( "Move image rank down" );
            $noRedirect = TRUE;
        } 
        // move file rank up
        elseif(isset($_POST['fileIDmoveUp']) && !empty($_POST['fileIDmoveUp']))
        {
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_file'    => (int)$_POST['fileIDmoveUp'],
                                       'error'      => & $this->tplVar['error'],
                                       'dir'        => 'up') );  
                                       
            $this->addLogMessage( "Move file rank up" );
            $noRedirect = TRUE;
        }
        // move file rank down
        elseif(isset($_POST['fileIDmoveDown']) && !empty($_POST['fileIDmoveDown']))
        {   
            $this->model->action('article','moveItemRank',
                                 array('id_article' => (int)$this->current_id_article,
                                       'id_file'    => (int)$_POST['fileIDmoveDown'],
                                       'error'      => & $this->tplVar['error'],
                                       'dir'        => 'down') );  
                                       
            $this->addLogMessage( "Move file rank down" );
            $noRedirect = TRUE;
        } 
        // add file
        elseif(isset($_POST['uploadfile']) && !empty($_POST['uploadfile']))
        {          
            $this->model->action('article','addItem',
                                 array('item'        => 'file',
                                       'id_article'  => (int)$this->current_id_article,
                                       'postName'    => 'ufile',
                                       'error'       => & $this->tplVar['error']) );
                                       
            $this->addLogMessage( "Upload file" );
            $noRedirect = TRUE;
        }
        // delete file
        elseif(isset($_POST['fileID2del']) && !empty($_POST['fileID2del']))
        {   
            $this->model->action('article','deleteItem',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error'],
                                       'id_file'    => (int)$_POST['fileID2del']) );
                                       
            $this->addLogMessage( "Delete files" );
            $noRedirect = TRUE;
        }  
        
        // update picture data if there images
        if(isset($_POST['picid']) && is_array($_POST['picid']))
        {
            $this->model->action( 'article','updateItem',
                                  array('item'    => 'pic',
                                        'error'   => & $this->tplVar['error'],
                                        'ids'     => &$_POST['picid'],
                                        'fields'  => array('description' => $this->stripSlashesArray($_POST['picdesc']),
                                                           'title'       => $this->stripSlashesArray($_POST['pictitle']))));

            $this->addLogMessage( "Update pictures data" );
            $noRedirect = TRUE;
        }        

        // update file data if there file attachments
        if(isset($_POST['fileid']) && is_array($_POST['fileid']))
        {
            $this->model->action( 'article','updateItem',
                                  array('item'    => 'file',
                                        'error'   => & $this->tplVar['error'],
                                        'ids'     => &$_POST['fileid'],
                                        'fields'  => array('description' => $this->stripSlashesArray($_POST['filedesc']),
                                                           'title'       => $this->stripSlashesArray($_POST['filetitle']))));
            
            $this->addLogMessage( "Update files data" );
            $noRedirect = TRUE;
        }  

        // if no error occure update text data
        if(count($this->tplVar['error']) == 0)
        {
            $articleFields = array('title'  => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                                   'body'   => SmartCommonUtil::stripSlashes((string)$_POST['body']));

            // add fields depended on configuration settings
            $this->addSetArticleFields( $articleFields );         
    
            $this->model->action('article','updateArticle',
                                 array('id_article' => (int)$this->current_id_article,
                                       'error'      => & $this->tplVar['error'],
                                       'fields'     => $articleFields));   
                                       
            $this->addLogMessage( "Update article data fields" );
            $this->addLogEvent( 3 );

            if(isset($_POST['finishupdate']) && ($_POST['finishupdate']=='Submit'))
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
        if(!isset($_REQUEST['id_node']) || 
           preg_match("/[^0-9]+/",$_REQUEST['id_node']) )
        {
                return FALSE;
        } 
        $this->current_id_node = (int)$_REQUEST['id_node'];
                   

        // get article ID
        if(!isset($_REQUEST['id_article']) || 
           preg_match("/[^0-9]+/",$_REQUEST['id_article']) )
        {
            return FALSE;
        } 
        $this->current_id_article = (int)$_REQUEST['id_article'];
        
        // template variables
        //
        // article data
        $this->tplVar['id_article'] = $this->current_id_article;
        $this->tplVar['id_node']    = $this->current_id_node;
        
        $this->tplVar['article']  = array();
        $this->tplVar['file']     = array();
        $this->tplVar['thumb']    = array();
       
        // errors
        $this->tplVar['error']  = array(); 

        // assign template config vars
        foreach($this->config['article'] as $key => $val)
        {
            $this->tplVar[$key] = $val;
        }
        
        // set format template var, means how to format textarea content -> editor/wikki ?
        // 1 = text_wikki
        // 2 = tiny_mce
        if($this->config['article']['force_format'] != 0)
        {
            $this->tplVar['format'] = $this->config['article']['force_format'];
            $this->tplVar['show_format_switch'] = FALSE;
        }
        elseif(isset($_POST['format']))
        {
            if(!preg_match("/(1|2){1}/",$_POST['format']))
            {
                $this->tplVar['format'] = $this->config['article']['default_format'];
            }
            $this->tplVar['format'] = $_POST['format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        else
        {
            $this->tplVar['format'] = $this->config['article']['default_format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        
        return TRUE;
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
     * strip slashes from form fields
     *
     * @param array $var_array Associative array
     */
    private function stripSlashesArray( &$var_array)
    {
        $tmp_array = array();
        foreach($var_array as $f)
        {
            $tmp_array[] = preg_replace("/\"/","'",SmartCommonUtil::stripSlashes( $f ));
        }

        return $tmp_array;
    } 
    
    /**
     * Redirect to the editArticle view
     */
    private function redirect()
    {
        @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=article&view=editArticle&disableMainMenu=1&id_article='.$this->current_id_article.'&id_node='.$this->current_id_node);
        exit;      
    }  
    
    /**
     * add article fields to get depended on the configuration settings
     *
     */     
    private function addGetArticleFields( & $articleFields )
    {
        if($this->config['article']['use_overtitle'] == 1)
        {
            array_push($articleFields, 'overtitle');
        }
        if($this->config['article']['use_subtitle'] == 1)
        {
            array_push($articleFields, 'subtitle');
        }   
        if($this->config['article']['use_description'] == 1)
        {
            array_push($articleFields, 'description');
        }
        if($this->config['article']['use_header'] == 1)
        {
            array_push($articleFields, 'header');
        }   
        if($this->config['article']['use_ps'] == 1)
        {
            array_push($articleFields, 'ps');
        }
        if($this->config['article']['use_logo'] == 1)
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
        if($this->config['article']['use_overtitle'] == 1)
        {
            $articleFields['overtitle'] = SmartCommonUtil::stripSlashes((string)$_POST['overtitle']);
        }
        if($this->config['article']['use_subtitle'] == 1)
        {
            $articleFields['subtitle'] = SmartCommonUtil::stripSlashes((string)$_POST['subtitle']);
        }   
        if($this->config['article']['use_description'] == 1)
        {
            $articleFields['description'] = SmartCommonUtil::stripSlashes((string)$_POST['description']);
        }
        if($this->config['article']['use_header'] == 1)
        {
            $articleFields['header'] = SmartCommonUtil::stripSlashes((string)$_POST['header']);
        }   
        if($this->config['article']['use_ps'] == 1)
        {
            $articleFields['ps'] = SmartCommonUtil::stripSlashes((string)$_POST['ps']);
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
        if($this->config['user']['use_log'] == 0)
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
        if($this->config['user']['use_log'] == 0)
        {
            return;
        }
        $this->logMessage .= $message."\n";
    }
}

?>