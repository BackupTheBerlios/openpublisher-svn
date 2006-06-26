<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ViewArticle class
 *
 * 
 *
 */

class ViewArticle extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 0;
    
    /**
     * Execute the view of the "article" template
     */
    function perform()
    { 
        // dont proceed if an error occure
        if(isset( $this->dontPerform ))
        {
            return;
        }

        // init variables (see private function below)
        $this->initVars();

        // We need an other template for the ajax demonstration (german/english article)
        // "Smart3 and Ajax" article
        if($this->current_id_article == 7)
        {
            $this->template = 'ArticleAjax';
        }

        // get article data                                                    
        $this->model->action('article','getArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'result'  => & $this->tplVar['article'],
                                   'status'  => array('>=',4),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'fields'  => array('id_article','id_node','title',
                                                      'header','overtitle','media_folder',
                                                      'subtitle','body','ps',
                                                      'pubdate','modifydate',
                                                      'allow_comment','close_comment') ));  
          
        // get node title and id of the article node
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->tplVar['article']['id_node'],
                                   'fields'  => array('title','id_node')));                             
    
        // get navigation node branch content of the requested article
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->tplVar['nodeBranch'],
                                   'id_node' => (int)$this->tplVar['article']['id_node'],
                                   'fields'  => array('title','id_node')));  
                                 
        // get article attached files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->tplVar['articleFiles'],
                                   'id_article' => array((int)$this->current_id_article),
                                   'status'     => array('>=',4),
                                   'order'      => array('rank','ASC'),
                                   'fields'     => array('id_file','file',
                                                         'size','mime',
                                                         'title','description')) );   

        // get article related keywords
        $keywords = array();
        $this->model->action('article','getKeywordIds', 
                             array('result'     => & $keywords,
                                   'id_article' => (int)$this->current_id_article,
                                   'key_status' => array('=', 2) ));     

        // if there are article related keywords, 
        if(count($keywords) > 0)
        {
            // get articles which have the same keywords
            // except the current article
            $exclude_id_article = array( $this->current_id_article );
            $this->model->action('article','fromKeyword',
                                 array('id_key_list' => & $keywords,
                                       'result'      => & $this->tplVar['keywordArticle'],
                                       'exclude'     => & $exclude_id_article,
                                       'status'      => array('>=', 4),
                                       'node_status' => array('>=', 2),
                                       'pubdate'     => array('<=', 'CURRENT_TIMESTAMP'),
                                       'fields'      => array('id_article','id_node','title') )); 
 
            // get links which have the same keywords as the current article
            $this->model->action('link','fromKeyword',
                                 array('id_key_list' => & $keywords,
                                       'result'      => & $this->tplVar['keywordLink'],
                                       'status'      => array('=', 2),
                                       'fields'      => array('id_link','url','title','description') )); 
        }
        
        // Should we show and allow article comments and show the comment form
        //
        // $this->config['article']['use_comment'] == 1 
        // --------------------------------------------
        // global enables to add comments for all articles
        //
        // $this->tplVar['article']['allow_comment'] == 1
        // ----------------------------------------------
        // Allow comments for just this article
        //
        //
        if(( $this->config['article']['use_comment']   == 1 ) &&
             $this->tplVar['article']['allow_comment'] == 1 )
        {
            $this->tplVar['commentMessage'] = '';
            $this->tplVar['cauthor'] = '';
            $this->tplVar['cemail']  = '';
            $this->tplVar['curl']    = '';
            $this->tplVar['cbody']   = '';
            
            // Do we show comments but not the add comment form?
            // Means: Visitors can no more add comments
            //
            if($this->tplVar['article']['close_comment'] == 0)
            {
                $this->tplVar['showCommentForm'] = TRUE;
                // create capcha picture and public key
                $this->model->action( 'common','captchaMake',
                                      array( 'captcha_pic' => &$this->tplVar['captcha_pic'],
                                             'public_key'  => &$this->tplVar['public_key'],
                                             'configPath'  => &$this->config['config_path']));                

                // add comment
                if(isset($_POST['addComment']) || isset($_POST['previewComment']))
                {
                    $this->addComment();
                }
            }
            
            // get article comments
            $this->model->action('article','comments',
                               array('result'     => & $this->tplVar['articleComments'],
                                     'id_article' => (int)$this->current_id_article,
                                     'status'     => array('=', 2),
                                     'fields'     => array('id_comment','pubdate',
                                                           'body','id_user',
                                                           'author','email','url') )); 

            // add html code to comments (ex.: nl2br)
            foreach($this->tplVar['articleComments'] as & $comment)
            {
                $comment['body'] = $this->addHtmlToComments( $comment['body'] );
            }
        }
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
        // Check if the visitor is a logged user
        //
        if(NULL == ($this->viewVar['loggedUserId'] = $this->model->session->get('loggedUserId')))
        {
            $this->tplVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->tplVar['isUserLogged'] = TRUE;
        }

        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');
        
        if( ($this->tplVar['isUserLogged'] == TRUE) && ($this->viewVar['loggedUserRole'] < 100) )
        {
            $this->tplVar['showEditLink'] = TRUE; 
        } 
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
        
        // validate id_article and view request var
        // 
        if( !isset($_GET['id_article'])   || 
            is_array($_GET['id_article']) || 
            preg_match("/[^0-9]+/",$_GET['id_article']) ) 
        {
            $this->template          = 'error';   
            $this->tplVar['message'] = "Wrong id_article value";
            $this->dontPerform = TRUE;
            return; 
        }           
        else
        {
            $this->current_id_article = (int)$_GET['id_article'];         
        }
        
        // check permission to access this article if it has status protected
        $this->checkPermission();
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->tplVar['node']         = array();
        $this->tplVar['nodeBranch']   = array();
        $this->tplVar['articleFiles'] = array();
        $this->tplVar['article']      = array();

        // init template variable for keyword related articles
        $this->tplVar['keywordArticle'] = array();
        // init template variable for keyword related links
        $this->tplVar['keywordLink'] = array();
        // article comments template array
        $this->tplVar['articleComments'] = array();

        // init captcha vars
        $this->tplVar['captcha_pic'] = '';
        $this->tplVar['public_key']  = '';
        
        // template var with charset used for the html pages
        $this->tplVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->tplVar['cssFolder'] = & $this->config['css_folder'];
        
        // we need this template vars to show admin links if the user is logged
        $this->tplVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->tplVar['adminWebController']  = $this->config['admin_web_controller'];
    }

    /**
     * check permission to access this article
     * only if the article has the status protect
     *
     */        
    private function checkPermission()
    {
        $result = array();
        // get article status and its node status
        $valide = $this->model->action('article','getStatus', 
                                       array('id_article' => (int)$this->current_id_article,
                                             'result'     => & $result));  

        if( ($valide == FALSE)             ||
            ($result['nodeStatus']    < 2) || 
            ($result['articleStatus'] < 4))
        {
            $this->template          = 'error'; 
            $this->tplVar['message'] = "The requested article isnt accessible";
            // template var with charset used for the html pages
            $this->tplVar['charset'] = & $this->config['charset'];   
            
            $this->dontPerform = TRUE;
            // disable caching
            $this->cacheExpire = 0;
            return;
        } 

        if( $this->tplVar['isUserLogged'] == FALSE )
        {
            // the requested article is only available for registered users
            if( ($result['nodeStatus']    == 3) || 
                ($result['articleStatus'] == 5) )
            {
                // set url vars to come back to this page after login
                $this->model->session->set('url','id_article='.$this->current_id_article);
                // switch to the login page
                @header('Location: '.SMART_CONTROLLER.'?view=login');
                exit;
            }
        }
    }
    /**
     * add article comment
     *
     */     
    private function addComment()
    {
        // validate captcha turing/public keys
        if (FALSE == $this->model->action( 'common','captchaValidate',
                                           array('turing_key'  => (string)$_POST['captcha_turing_key'],
                                                 'public_key'  => (string)$_POST['captcha_public_key'],
                                                 'configPath'  => (string)$this->config['config_path'])))
        {
            $this->tplVar['commentMessage'] = 'Wrong turing key';
            $this->resetFormData();
            return TRUE;
        }
        
        if( FALSE == $this->validateEmail( $_POST['cemail'] )  )
        {
            $this->resetFormData();
            return TRUE;
        }

        if( empty($_POST['author']) )
        {
            $_POST['author'] = 'annonymous';
        }  
        
        // assign template vars for comment preview
        //
        if( isset($_POST['previewComment']) )
        {
            $this->tplVar['showCommentPreview'] = TRUE;
            
            $this->tplVar['commentPreview']['author'] = $this->strip( $_POST['cauthor'] );
            $this->tplVar['commentPreview']['url']    = $this->strip( $_POST['curl'] );
            $this->tplVar['commentPreview']['email']  = $this->strip( $_POST['cemail'] );
            $this->tplVar['commentPreview']['body']   = $this->addHtmlToComments( $this->strip( $_POST['cbody'] ) );
            $this->resetFormData();
            return TRUE;
        }
        
        if(!empty($_POST['cbody']))
        {
            $this->model->action('article', 'addComment',
                   array('fields' => array('id_article' => (int)$this->current_id_article,
                                           'author'     => (string) $this->strip( $_POST['cauthor'] ),
                                           'url'        => (string) $this->strip( $_POST['curl'] ),
                                           'email'      => (string) $this->strip( $_POST['cemail'] ),
                                           'body'       => (string) $this->strip( $_POST['cbody'] )) ));


            // Send emails if a new comment was made?
            // $this->sendEmails();

            // comment needs to be validate
            if($this->config['article']['default_comment_status'] == 1)
            {
                $this->tplVar['commentMessage'] = 'Thanks for your comment. Your comment will be reviewed as soon as possible.';
            }
            else
            {
                header('Location: index.php?id_article='.$this->current_id_article.'#comments');
                exit;
            }
        }
        else
        {
            $this->resetFormData();
            $this->tplVar['commentMessage'] = "Comment area is empty!";
        }
    }
    /**
     * validate email field
     *
     */     
    private function validateEmail( &$email )
    {
        if(!strstr($email, "\n")  &&
           (strlen($email) < 255) )
        {
            return TRUE;
        }
        else
        {
            $this->tplVar['commentMessage'] = "The email field has wrong values!";
            return FALSE;
        }    
    }
    /**
     * strip bad code
     *
     */     
    private function strip( $str )
    {
        return $this->model->action( 'common', 'safeHtml', strip_tags( $str ) );   
    }
    /**
     * fill form fields with old data
     *
     */     
    private function resetFormData()
    {
        $this->tplVar['cauthor'] = htmlentities($this->strip($_POST['cauthor']), ENT_COMPAT, $this->config['charset']);     
        $this->tplVar['cemail']  = htmlentities($this->strip($_POST['cemail']), ENT_COMPAT, $this->config['charset']);     
        $this->tplVar['curl']    = htmlentities($this->strip($_POST['curl']), ENT_COMPAT, $this->config['charset']);     
        $this->tplVar['cbody']   = htmlentities($this->strip($_POST['cbody']), ENT_COMPAT, $this->config['charset']); 
    }  
    /**
     * send email(s) on new comments
     *
     */      
    private function sendEmails()
    {
        // get emails of administrators
        $adminEmails = array();
        $this->model->action('user','getUsers',
                             array('result'  => & $adminEmails,
                                   'role'    => array('=',20),
                                   'status'  => array('=',2),
                                   'fields'  => array('email') ));   

        $adminBody  = 'Hi,<br>A new comment was added to the following article:';        
        $adminBody .= '<a href="'.$this->config['site_url'].'?id_article='.$this->tplVar['article']['id_article'].'">'.$this->tplVar['article']['title'].'</a>';
        
        if($this->config['article']['default_comment_status'] == 1)
        {
            $adminBody .= '<br><br>You have to validate new comments!';  
        }
        
        $this->model->action('common', 'sendMail',
                             array('toEmail'   => & $adminEmails,
                                   'fromEmail' => '',
                                   'subject'   => 'New comment added',
                                   'body'      => (string)$adminBody));     
                                   
    }    

    /**
     * Parse comments for phpBB code
     *
     */
    private function addHtmlToComments( $body )
    {
        $this->model->action('article', 'phpBBParseComment', 
                             array('content' => & $body) );
        return $body;
    }
}

?>