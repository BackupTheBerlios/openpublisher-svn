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
 * ControllerArticleRPC class
 *
 * This controller class is the same as the article controller except
 * that there is an additional rpc (remote procedure call)
 * See: Line 78
 *
 */

class ControllerArticleRPC extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the controller of the "ArticleRPC" view
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();

        // get article data                                                    
        $this->model->action('article','getArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'result'  => & $this->viewVar['article'],
                                   'status'  => array('>=',4),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'fields'  => array('id_article','id_node','title',
                                                      'header','overtitle','media_folder',
                                                      'subtitle','body','ps',
                                                      'pubdate','modifydate',
                                                      'allow_comment','close_comment') ));  
          
        // get node title and id of the article node
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->viewVar['article']['id_node'],
                                   'fields'  => array('title','id_node')));                             
    
        // get navigation node branch content of the requested article
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['nodeBranch'],
                                   'id_node' => (int)$this->viewVar['article']['id_node'],
                                   'fields'  => array('title','id_node')));  
                                 
        // get article attached files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->viewVar['articleFiles'],
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
        
        // do remote procedure call (RPC) to the distributed server
        // with this package.
        // The rpc call is done through an action class which is delivered
        // with the article module and which od course optimized
        // to interact with the article module.
        // fetch 10 latest modified articles
        //        
        // you have to modify some vars if you make calls
        // to an other domain server
        $url = parse_url('http://'.$_SERVER["REMOTE_ADDR"].$_SERVER["REQUEST_URI"]);
        $this->model->action('article', 'getArticlesRPC',
                             array('result'      => & $this->viewVar['rpcArticles'],
                                   'domain'      => $this->router->getHost(),
                                   'rpcServer'   => $this->viewVar['urlBase'],
                                   'query'       => '/Rpc/cntr/rpcServeArticles',
                                   'authUser'    => 'superuser',
                                   'authPasswd'  => 'a',
                                   'debug'       => null,
                                   'numArticles' => 10) );                       

        // if there are article related keywords, 
        if(count($keywords) > 0)
        {
            // get articles which have the same keywords
            // except the current article
            $exclude_id_article = array( $this->current_id_article );
            $this->model->action('article','fromKeyword',
                                 array('id_key_list' => & $keywords,
                                       'result'      => & $this->viewVar['keywordArticle'],
                                       'exclude'     => & $exclude_id_article,
                                       'status'      => array('>=', 4),
                                       'node_status' => array('>=', 2),
                                       'pubdate'     => array('<=', 'CURRENT_TIMESTAMP'),
                                       'fields'      => array('id_article','id_node','title') )); 
 
            // get links which have the same keywords as the current article
            $this->model->action('link','fromKeyword',
                                 array('id_key_list' => & $keywords,
                                       'result'      => & $this->viewVar['keywordLink'],
                                       'status'      => array('=', 2),
                                       'fields'      => array('id_link','url','title','description') )); 
        }
        
        // Should we show and allow article comments and show the comment form
        //
        // $this->config->getModuleVar('article', 'use_comment') == 1 
        // --------------------------------------------
        // global enables to add comments for all articles
        //
        // $this->config->getModuleVar('article', 'allow_comment') == 1
        // ----------------------------------------------
        // Allow comments for just this article
        //
        //
        if(( $this->config->getModuleVar('article', 'use_comment')   == 1 ) &&
             $this->config->getModuleVar('article', 'allow_comment') == 1 )
        {
            $this->viewVar['commentMessage'] = '';
            $this->viewVar['cauthor'] = '';
            $this->viewVar['cemail']  = '';
            $this->viewVar['curl']    = '';
            $this->viewVar['cbody']   = '';
            
            // Do we show comments but not the add comment form?
            // Means: Visitors can no more add comments
            //
            if($this->viewVar['article']['close_comment'] == 0)
            {
                $this->viewVar['showCommentForm'] = TRUE;
                // create capcha picture and public key
                $this->model->action( 'common','captchaMake',
                                      array( 'captcha_pic' => &$this->viewVar['captcha_pic'],
                                             'public_key'  => &$this->viewVar['public_key'],
                                             'configPath'  => $this->config->getVar('config_path')));                

                $addComment     = $this->httpRequest->getParameter('addComment', 'post', 'alpha');
                $previewComment = $this->httpRequest->getParameter('previewComment', 'post', 'alpha');

                // add comment
                if(!empty($addComment) || !empty($previewComment))
                {
                    $this->addComment();
                }
            }
            
            // get article comments
            $this->model->action('article','comments',
                               array('result'     => & $this->viewVar['articleComments'],
                                     'id_article' => (int)$this->current_id_article,
                                     'status'     => array('=', 2),
                                     'fields'     => array('id_comment','pubdate',
                                                           'body','id_user',
                                                           'author','email','url') )); 

            // add html code to comments (ex.: nl2br)
            foreach($this->viewVar['articleComments'] as & $comment)
            {
                $comment['body'] = $this->addHtmlToComments( $comment['body'] );
            }
        }
        
        $this->viewVar['header']      = $this->controllerLoader->header();
        $this->viewVar['footer']      = $this->controllerLoader->footer();  
        $this->viewVar['rightBorder'] = $this->controllerLoader->rightBorder();   
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
            $this->viewVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->viewVar['isUserLogged'] = TRUE;
        }

        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');
        
        if( ($this->viewVar['isUserLogged'] == TRUE) && ($this->viewVar['loggedUserRole'] < 100) )
        {
            $this->viewVar['showEditLink'] = TRUE; 
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
        
        $this->current_id_article = $this->httpRequest->getParameter('id_article', 'get', 'int');
        
        // get cache var
        // when post comments the cache is disabled
        $get_cache_time = $this->httpRequest->getParameter('cache', 'get', 'int');
        if((false !== $get_cache_time) && ($get_cache_time == 0))
        {
            $this->cacheExpire = 0;
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
        $this->viewVar['node']         = array();
        $this->viewVar['nodeBranch']   = array();
        $this->viewVar['articleFiles'] = array();
        $this->viewVar['article']      = array();

        // init template variable for keyword related articles
        $this->viewVar['keywordArticle'] = array();
        // init template variable for keyword related links
        $this->viewVar['keywordLink'] = array();
        // article comments template array
        $this->viewVar['articleComments'] = array();
        // articles fetched through RPC template array
        $this->viewVar['rpcArticles']     = array();

        // init captcha vars
        $this->viewVar['captcha_pic'] = '';
        $this->viewVar['public_key']  = '';
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = $this->config->getModuleVar('common', 'charset');
        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller'); 
        
        // template var with css folder
        $this->viewVar['cssFolder']    = JAPA_PUBLIC_DIR . 'styles/'.$this->config->getModuleVar('common', 'styles_folder');
        $this->viewVar['scriptFolder'] = JAPA_PUBLIC_DIR . 'scripts/default/';
        $this->viewVar['urlBase'] = $this->router->getBase();
        $this->viewVar['urlAjax'] = $this->viewVar['urlBase'];
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
            $this->router->redirect(); 
        } 

        if( $this->viewVar['isUserLogged'] == FALSE )
        {
            // the requested article is only available for registered users
            if( ($result['nodeStatus']    == 3) || 
                ($result['articleStatus'] == 5) )
            {
                // set url vars to come back to this page after login
                $this->model->session->set('url','id_article/'.$this->current_id_article);
                // switch to the login page
                $this->router->redirect( 'cntr/login' ); 
            }
        }
    }
    /**
     * add article comment
     *
     */     
    private function addComment()
    {
        $captcha_turing_key = trim($this->httpRequest->getParameter('captcha_turing_key', 'post', 'alnum'));
        $captcha_public_key = trim($this->httpRequest->getParameter('captcha_public_key', 'post', 'alnum'));

        $this->cauthor = trim($this->httpRequest->getParameter('cauthor', 'post', 'raw'));
        $this->curl    = trim($this->httpRequest->getParameter('curl', 'post', 'raw'));
        $this->cemail  = trim($this->httpRequest->getParameter('cemail', 'post', 'raw'));
        $this->cbody   = trim($this->httpRequest->getParameter('cbody', 'post', 'raw'));
        
        // validate captcha turing/public keys
        if (false == $this->model->action( 'common','captchaValidate',
                                           array('turing_key'  => (string)$captcha_turing_key,
                                                 'public_key'  => (string)$captcha_public_key,
                                                 'configPath'  => (string)$this->config->getVar('config_path'))))
        {
            $this->viewVar['commentMessage'] = 'Wrong turing key';
            $this->resetFormData();
            return true;
        }

        $cemail = $this->httpRequest->getParameter('cemail', 'post', 'raw');
        
        if( false == $this->validateEmail( $cemail )  )
        {
            $this->resetFormData();
            return true;
        }

        $author = $this->httpRequest->getParameter('author', 'post', 'raw');

        if( empty($author) )
        {
            $author = 'annonymous';
        }  

        $previewComment = $this->httpRequest->getParameter('previewComment', 'post', 'alnum');
        
        // assign template vars for comment preview
        //
        if( !empty($previewComment) )
        {
            $this->viewVar['showCommentPreview'] = true;
            
            $this->viewVar['commentPreview']['author'] = $this->strip( $this->cauthor );
            $this->viewVar['commentPreview']['url']    = $this->strip( $this->curl );
            $this->viewVar['commentPreview']['email']  = $this->strip( $this->cemail );
            $this->viewVar['commentPreview']['body']   = $this->addHtmlToComments( $this->strip( $this->cbody ) );
            $this->resetFormData();
            return true;
        }
        
        if(!empty($this->cbody))
        {
            $this->model->action('article', 'addComment',
                   array('fields' => array('id_article' => (int)$this->current_id_article,
                                           'author'     => (string) $this->strip( $this->cauthor ),
                                           'url'        => (string) $this->strip( $this->curl ),
                                           'email'      => (string) $this->strip( $this->cemail ),
                                           'body'       => (string) $this->strip( $this->cbody )) ));


            // Send emails if a new comment was made?
            // $this->sendEmails();

            // comment needs to be validate
            if($this->config->getModuleVar('article', 'default_comment_status') == 1)
            {
                $this->viewVar['commentMessage'] = 'Thanks for your comment. Your comment will be reviewed as soon as possible.';
            }
            else
            {
                $this->router->redirect( 'id_article/'.$this->current_id_article.'#comments' ); 
            }
        }
        else
        {
            $this->resetFormData();
            $this->viewVar['commentMessage'] = "Comment area is empty!";
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
            $this->viewVar['commentMessage'] = "The email field has wrong values!";
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
        $_charset = $this->config->getModuleVar('common', 'charset');
        
        $this->viewVar['cauthor'] = htmlentities($this->strip($this->cauthor), ENT_COMPAT, $_charset);     
        $this->viewVar['cemail']  = htmlentities($this->strip($this->cemail), ENT_COMPAT, $_charset);     
        $this->viewVar['curl']    = htmlentities($this->strip($this->curl), ENT_COMPAT, $_charset);     
        $this->viewVar['cbody']   = htmlentities($this->strip($this->cbody), ENT_COMPAT, $_charset); 
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
        $adminBody .= '<a href="http://'.$this->router->getBase().'/id_article/'.$this->viewVar['article']['id_article'].'">'.$this->config->getModuleVar('article','title').'</a>';
        
        if($this->config->getModuleVar('article','default_comment_status') == 1)
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