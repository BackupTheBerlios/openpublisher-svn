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
 * ViewSearch class
 *
 */

class ControllerSearch extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "search" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
          
        // search articles                                                   
        $this->model->action('article','search',
                             array('result'     => & $this->viewVar['articles'], 
                                   'search'     => (string)$this->searchString,
                                   'status'     => array('=', 4),
                                   'nodeStatus' => array('>=', 2),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),                                   
                                   'fields'  => array('id_article','title',
                                                      'id_node','description') ));  

        // get node + node branch of each article
        foreach($this->viewVar['articles'] as & $article)
        {
            $article['nodeBranch'] = array();
            $article['node']       = array();
            
            // get navigation node branch content of the article node
            $this->model->action('navigation','getBranch', 
                             array('result'  => & $article['nodeBranch'],
                                   'id_node' => (int)$article['id_node'],
                                   'fields'  => array('title','id_node','id_parent')));   
                                   
            // get article node content
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $article['node'],
                                       'id_node' => (int)$article['id_node'],
                                       'fields'  => array('title','id_node')));
        }
        
        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->viewVar['pager'],
                                   'search'     => (string)$this->searchString,
                                   'status'     => array('=','4'),
                                   'nodeStatus' => array('>=', 2),
                                   'pubdate'    => array('<=', 'CURRENT_TIMESTAMP'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => $this->viewVar['urlBase'].'/cntr/search/search/'.$this->pagerUrlSearchString,
                                   'var_prefix' => 'search_',
                                   'css_class'  => 'search_pager'));    
                               
        // get result of the header and footer controller
        // 
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
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
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
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        $this->searchString = $this->httpRequest->getParameter( 'search', 'request', 'raw' );

        $this->searchString = JapaCommonUtil::stripSlashes((string)$this->searchString);
        $this->pagerUrlSearchString = urlencode(JapaCommonUtil::stripSlashes((string)$this->searchString));
        
        // strip bad code
        $this->searchString = $this->model->action( 'common', 'safeHtml', strip_tags($this->searchString) );
        
        // assign template variable with search string
        $this->viewVar['search']     = $this->searchString;
        $this->viewVar['formsearch'] = $this->searchString;
        
        // template array variables
        $this->viewVar['articles'] = array();
        $this->viewVar['pager']    = '';
        
        // set articles limit per page
        $this->articlesPerPage = 10;
        
        // get current article pager page
        if(!isset($_GET['search_page']))
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$_GET['search_page'];
        }
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->viewVar['cssFolder'] = & $this->config['css_folder'];
        
        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController']  = 'Module';        
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        $this->viewVar['urlBase'] = $this->httpRequest->getBaseUrl();
        $this->viewVar['urlCss'] = 'http://'.$this->router->getHost().$this->viewVar['urlBase'].'/'.$this->viewVar['cssFolder'];      
    }
}

?>