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
 * ViewNode class
 *
 */

class ControllerBlogMain extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "node" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
        
        // dont proceed if an error occure
        if(isset( $this->dontPerform ))
        {
            return;
        }   

         // get requested node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title')));

        // get navigation node branch content of the requested node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['nodeBranch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','id_node')));  
         
        // get child nodes content of the requested node
        // only with status=2, means active      
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->viewVar['childNodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','id_node'))); 

        // id_node 2 is the top level node "Blog". Means this node is a sector.
        // Here we show all articles of this sector and all sub categories
        // 
        if($this->current_id_node == 2)
        {
            $id_item = 'id_sector';
        }
        else
        {
            $id_item = 'id_node';
        }

        // get node related article titles count by 10                                                     
        $this->model->action('article','getArticles', 
                              array($id_item  => array((int)$this->current_id_node),
                                    'result'  => & $this->viewVar['allArticles'],
                                    'status'  => array('>=', 4),
                                    'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                    'order'   => array('pubdate', 'desc'),
                                    'limit'   => array('perPage' => (int)$this->articlesPerPage,
                                                       'numPage' => (int)$this->pageNumber),
                                    'fields'  => array('id_article','title','body',
                                                       'id_node','pubdate', 'num_comments') ) );
        
        // get node titles of each article
        $this->getArticlesNodes();

        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->viewVar['pager'],
                                   $id_item     => array((int)$this->current_id_node),
                                   'status'     => array('>=', '4'),
                                   'pubdate'    => array('<=', 'CURRENT_TIMESTAMP'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => $this->viewVar['urlBase'].'/id_node/'.$this->current_id_node,
                                   'var_prefix' => 'article_',
                                   'css_class'  => 'smart_pager'));  
        
        // build rss file
        $this->rssBuilder();
        
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

        if( ($this->current_id_node === null) ) 
        {
              @header('Location: /');
              exit;
        }
        
        // check if the demanded node has at least status 2
        $nodeStatus = $this->model->action('navigation','getNodeStatus', 
                                            array('id_node' => (int)$this->current_id_node));  
        
        // if the requested node isnt active
        if( $nodeStatus < 2 )
        {
            $this->template          = 'error'; 
            $this->viewVar['message'] = "The requested node isnt accessible";
            $this->dontPerform       = TRUE;
            // disable caching
            $this->cacheExpire = 0;
        } 
        // if the requested node is only available for registered users
        elseif( ($nodeStatus == 3) && ($this->viewVar['isUserLogged'] == FALSE) )
        {
              // set url vars to come back to this page after login
              $this->model->session->set('url','id_node='.$this->current_id_node);
              // switch to the login page
              @header('Location: '.SMART_CONTROLLER.'?view=login');
              exit;
        }
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->viewVar['node']         = array();
        $this->viewVar['childNodes']   = array();
        $this->viewVar['nodeBranch']   = array();
        $this->viewVar['nodeFiles']    = array();
        $this->viewVar['allArticles']  = array();
        $this->viewVar['links']        = array();
        $this->viewVar['pager']        = '';

        // set articles limit per page
        $this->articlesPerPage = 20;
        // get current article pager page
        if(!isset($_GET['article_page']))
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$_GET['article_page'];
        }
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->viewVar['cssFolder'] = & $this->config['css_folder'];
        
        // init template variable for keyword related links
        $this->viewVar['keywordLink'] = array();   

        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];  
        $this->viewVar['adminWebController'] = $this->config['default_module_application_controller']; 
        
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        $this->viewVar['urlBase'] = 'http://'.$this->router->getHost().$this->httpRequest->getBaseUrl();
        $this->viewVar['urlCss'] = $this->viewVar['urlBase'].'/'.$this->viewVar['cssFolder'];  
    }

    /**
     * get node title of each article
     *
     */ 
    private function getArticlesNodes()
    {
        foreach($this->viewVar['allArticles'] as & $article)
        {
            $article['node'] = array();
            // get requested node content
            $this->model->action('navigation','getNode', 
                                  array('result'  => & $article['node'],
                                        'id_node' => (int)$article['id_node'],
                                        'fields'  => array('title')));
        }
    }

    /**
     * Build rss file with article titles of the current node
     *
     */     
    private function rssBuilder()
    {
        $this->viewVar['node']['rssfile'] = '';
        
        $this->model->action('article','feedCreator',
               array('format'       => 'rss',
                     'output'       => 'save',
                     'id'           => 'blog',
                     'items'        => & $this->viewVar['allArticles'],
                     'rssfile'      => & $this->viewVar['node']['rssfile'],
                     'expire'       => 3600,
                     'channel' => array('about'    => 'http://www.smart3.org',
                                        'link'     => 'http://www.smart3.org',
                                        'desc'     => 'test',
                                        'title'    => 'Smart3 php5 framework - BLOG'),
                     'baseUrl'    => 'http://www.open-publisher.net/index.php?id_article='
                     ) );
                                                       
    }
}

?>