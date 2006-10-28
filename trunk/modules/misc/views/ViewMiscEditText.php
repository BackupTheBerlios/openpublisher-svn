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
 * ViewMiscEditText
 *
 */
 
class ViewMiscEditText extends JapaControllerAbstractPage
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'edittext';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/misc/templates/';
    
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
            $this->tplVar['error'] = 'You have not the rights to edit a text!';
            $this->dontPerform = TRUE;
        }
        // check if the demanded text exists
        elseif($this->textExists() == FALSE)
        {
            $this->tplVar['error'] = 'The requested text dosent exists!';
            $this->dontPerform = TRUE;                 
        }
        // is text locked by an other user
        elseif( TRUE !== $this->lockText() )
        {
            $this->tplVar['error'] = 'The requested text is locked by an other user!';
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
        
        // change nothing and switch back
        if(isset($_POST['canceledit']) && ($_POST['canceledit'] == '1'))
        {
            $this->unlocktext();
            $this->redirect();        
        }

        // update text data
        if( isset($_POST['modifytextdata']) )
        {      
            $this->updateTextData();
        }
        
        // get current text data
        $this->model->action('misc','getText', 
                             array('result'  => & $this->tplVar['text'],
                                   'id_text' => (int)$this->current_id_text,
                                   'error'   => & $this->tplVar['error'],
                                   'fields'  => array('title','body','description',
                                                      'media_folder','status',
                                                      'id_text','format')));
        
        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['text'], array('title') );        
                                       
        // get user picture thumbnails
        $this->model->action('misc','getAllThumbs',
                             array('result'  => & $this->tplVar['thumb'],
                                   'id_text' => (int)$_REQUEST['id_text'],
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
        foreach($this->tplVar['thumb'] as $thumb)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['thumb'][$x], array('description') );
            $this->tplVar['thumb'][$x]['description'] = addslashes($this->tplVar['thumb'][$x]['description']);
            $x++;
        }

        // get user files
        $this->model->action('misc','getAllFiles',
                             array('result'  => & $this->tplVar['file'],
                                   'id_text' => (int)$_REQUEST['id_text'],
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );

        // convert files description field to safely include into javascript function call
        $x=0;
        foreach($this->tplVar['file'] as $file)
        {
            $this->convertHtmlSpecialChars( $this->tplVar['file'][$x], array('description') );
            $this->tplVar['file'][$x]['description'] = addslashes($this->tplVar['file'][$x]['description']);
            $x++;
        } 
        
        // we need the url vars to open this page by the keyword map window
        if($this->config['misc']['use_keywords'] == 1)
        {
            if(isset($_REQUEST['addkey']))
            {
                $this->addKeyword();
            }
            $this->getKeywords();
        }        
    }  

    private function updatetextData()
    {
        $use_text_format = FALSE;

        if(empty($_POST['title']))
        {
            $this->tplVar['error'] = 'text title is empty!';
            return;
        }
            
        if($_POST['delete_text'] == '1')
        {
            $this->deletetext( $_POST['id_text'] );
            $this->redirect();
        }                 
        // add picture
        elseif(isset($_POST['uploadpicture']) && !empty($_POST['uploadpicture']))
        {   
            $this->model->action('misc','addItem',
                                 array('item'     => 'picture',
                                       'id_text'  => (int)$_REQUEST['id_text'],
                                       'postName' => 'picture',
                                       'error'    => & $this->tplVar['error']) ); 
        }
        // delete picture
        elseif(isset($_POST['imageID2del']) && !empty($_POST['imageID2del']))
        {
            $this->model->action('misc','deleteItem',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_pic'  => (int)$_POST['imageID2del']) ); 
        }
        // move image rank up
        elseif(isset($_POST['imageIDmoveUp']) && !empty($_POST['imageIDmoveUp']))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_pic'  => (int)$_POST['imageIDmoveUp'],
                                       'dir'     => 'up') ); 
        }  
        // move image rank down
        elseif(isset($_POST['imageIDmoveDown']) && !empty($_POST['imageIDmoveDown']))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_pic'  => (int)$_POST['imageIDmoveDown'],
                                       'dir'     => 'down') ); 
        } 
        // move file rank up
        elseif(isset($_POST['fileIDmoveUp']) && !empty($_POST['fileIDmoveUp']))
        {
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_file' => (int)$_POST['fileIDmoveUp'],
                                       'dir'     => 'up') );                                                 
        }
        // move file rank down
        elseif(isset($_POST['fileIDmoveDown']) && !empty($_POST['fileIDmoveDown']))
        {   
            $this->model->action('misc','moveItemRank',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_file' => (int)$_POST['fileIDmoveDown'],
                                       'dir'     => 'down') );                                                
        } 
        // add file
        elseif(isset($_POST['uploadfile']) && !empty($_POST['uploadfile']))
        {          
            $this->model->action('misc','addItem',
                                 array('item'     => 'file',
                                       'id_text'  => (int)$_REQUEST['id_text'],
                                       'postName' => 'ufile',
                                       'error'    => & $this->tplVar['error']) );                          
        }
        // delete file
        elseif(isset($_POST['fileID2del']) && !empty($_POST['fileID2del']))
        {   
            $this->model->action('misc','deleteItem',
                                 array('id_text' => (int)$_REQUEST['id_text'],
                                       'id_file' => (int)$_POST['fileID2del']) ); 
        }  
        
        // update picture data if there images
        if(isset($_POST['picid']) && is_array($_POST['picid']))
        {
            $this->model->action( 'misc','updateItem',
                                  array('item'    => 'pic',
                                        'ids'     => &$_POST['picid'],
                                        'fields'  => array('description' => &$_POST['picdesc'],
                                                           'title'       => &$_POST['pictitle'])));
        }        

        // update file data if there file attachments
        if(isset($_POST['fileid']) && is_array($_POST['fileid']))
        {
            $this->model->action( 'misc','updateItem',
                                  array('item'    => 'file',
                                        'ids'     => &$_POST['fileid'],
                                        'fields'  => array('description' => &$_POST['filedesc'],
                                                           'title'       => &$_POST['filetitle'])));
        }  
        
        // if no error occure update text data
        if(count($this->tplVar['error']) == 0)
        {
            // update text data
            $this->updatetext( $use_text_format );

            $this->deleteKeywords();

            if( isset($_POST['finishupdate']) )
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
                                          'by_id_user' => (int)$this->viewVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // fetch the current id_text. If no text the script assums that
        // we are at the top level with id_parent 0
        if( !isset($_REQUEST['id_text']) || preg_match("/[^0-9]+/",$_REQUEST['id_text']) ) 
        {
            $this->tplVar['id_text']  = 0;
            $this->current_id_text    = 0;      
        }
        else
        {
            $this->tplVar['id_text']  = (int)$_REQUEST['id_text'];
            $this->current_id_text    = (int)$_REQUEST['id_text'];          
        }     

        // set format template var, means how to format textarea content -> editor/wikki ?
        // 1 = text_wikki
        // 2 = tiny_mce
        if($this->config['misc']['force_format'] != 0)
        {
            $this->tplVar['format'] = $this->config['misc']['force_format'];
            $this->tplVar['show_format_switch'] = FALSE;
        }
        elseif(isset($_POST['format']))
        {
            if(!preg_match("/(1|2){1}/",$_POST['format']))
            {
                $this->tplVar['format'] = $this->config['misc']['default_format'];
            }
            $this->tplVar['format'] = $_POST['format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }
        else
        {
            $this->tplVar['format'] = $this->config['misc']['default_format'];
            $this->tplVar['show_format_switch'] = TRUE;
        }

        $this->tplVar['use_images']    = $this->config['misc']['use_images'];
        $this->tplVar['use_files']     = $this->config['misc']['use_files'];
        $this->tplVar['lock_text']     = 'unlock';
        
        // template variables
        //
        // data of the current text
        $this->tplVar['text']  = array(); 
        // data of thumbs an files attached to this text
        $this->tplVar['thumb'] = array();
        $this->tplVar['file']  = array();        
        // errors
        $this->tplVar['error']  = array();    

        // we need the url vars to open this page by the keyword map window
        if($this->config['misc']['use_keywords'] == 1)
        {
            $this->tplVar['opener_url_vars'] = base64_encode('&view=editText&id_text='.$this->current_id_text.'&disableMainMenu=1');
        }
        $this->tplVar['use_keywords'] = $this->config['misc']['use_keywords'];

        
        $this->dontPerform = FALSE; 
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
     * Update text data
     *
     * @param int $rank New rank
     */
    private function updatetext( $format )
    {
        $fields = array('status'      => (int)$_POST['status'],
                        'title'       => SmartCommonUtil::stripSlashes(strip_tags((string)$_POST['title'])),
                        'description' => SmartCommonUtil::stripSlashes((string)$_POST['description']),
                        'body'        => SmartCommonUtil::stripSlashes((string)$_POST['body']));

        if($format != FALSE)
        {
            $fields['format'] = $format;
        }        
        
        $this->model->action('misc','updateText',
                             array('id_text' => (int)$_REQUEST['id_text'],
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
        @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=misc');
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
        $this->model->action('misc','addKeyword', 
                             array('id_key'  => (int)$_REQUEST['id_key'],
                                   'id_text' => (int)$this->current_id_text));
    }  
    
    /**
     * get text related keywords
     *
     */      
    private function getKeywords()
    {
        $this->tplVar['keys'] = array();
        
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
            
            $this->tplVar['keys'][] = $tmp;
        }
        sort($this->tplVar['keys']);    
    }   
    /**
     * remove keyword relations
     *
     */      
    private function deleteKeywords()
    {
        if(isset($_POST['id_key']) && is_array($_POST['id_key']))
        {
            foreach($_POST['id_key'] as $id_key)
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