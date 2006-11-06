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
 * SimpleNodeArticle class
 */

class ControllerSimpleNodeArticle extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 300;
    
    /**
     * Execute the view of the "simpleNodeArticle" template
     */
    function perform()
    {     
        $this->initVars();

        // dont proceed if an error occure
        if(isset( $this->dontPerform ))
        {
            return;
        }
   
        // get article data                                                    
        $this->model->action('article','getArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'result'  => & $this->viewVar['article'],
                                   'status'  => array('>=',4),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'fields'  => array('id_article','id_node','title',
                                                      'header','overtitle','media_folder',
                                                      'subtitle','body','ps') ));     

        // get article attached files
        $this->model->action('article','getAllFiles',
                             array('result'     => & $this->viewVar['articleFiles'],
                                   'id_article' => array((int)$this->current_id_article),
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

        $this->current_id_node    = $this->httpRequest->getParameter( 'id_node', 'request', 'digits' );
        $this->current_id_article = $this->httpRequest->getParameter( 'id_article', 'request', 'digits' );   
             
        if( ($this->current_id_node === null) ) 
        {
              @header('Location: /');
              exit;
        }
        
             
        if( ($this->current_id_article === null) ) 
        {
              @header('Location: /');
              exit;
        }


            $node_article = array();
            // get node related article titles count by 10                                                     
            $this->model->action('article','getNodeArticles',
                                 array('id_node' => (int)$this->current_id_node,
                                       'result'  => & $node_article,
                                       'status'  => array('>=', 4),
                                       'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                       'order'   => array('rank', 'asc'),
                                       'limit'   => array('perPage' => 10,
                                                          'numPage' => 0),
                                       'fields'  => array('id_article') ));
            
            if(!isset($node_article[0]['id_article']))
            {
                $this->noIdArticle();
                return;
            }
            
            $this->current_id_article = (int)$node_article[0]['id_article'];

        
        // check permission to access this article if it has status protected
        $this->checkPermission();                  
    }

    /**
     * append filter chain
     *
     */
    public function appendFilterChain( & $outputBuffer )
    {
        // filter action of the common module that trims the html output
        $this->model->action( 'common', 'filterTrim', array('str' => & $outputBuffer) );        
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->viewVar['article'] = array();
        $this->viewVar['articleFiles'] = array();

        // init template variable for keyword related articles
        $this->viewVar['keywordArticle'] = array();
        // init template variable for keyword related links
        $this->viewVar['keywordLink'] = array(); 
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];

        
        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']      = $this->viewVar['loggedUserRole']; 
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        $this->viewVar['urlBase'] = $this->httpRequest->getBaseUrl();
        $this->viewVar['urlCss'] = 'http://'.$this->router->getHost().$this->viewVar['urlBase'].'/'.$this->viewVar['cssFolder'];
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
            $this->noIdArticle();
            return;
        } 

        if( $this->viewVar['isUserLogged'] == FALSE )
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
     * set error template
     *
     */        
    private function noIdArticle()
    {
            $this->template          = 'error'; 
            $this->viewVar['message'] = "The requested content isnt accessible";
            // template var with charset used for the html pages
            $this->viewVar['charset'] = & $this->config['charset'];   
            
            $this->dontPerform = TRUE;
            // disable caching
            $this->cacheExpire = 0;
    }
}

?>