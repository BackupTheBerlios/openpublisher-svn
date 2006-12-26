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
 * ControllerSearch class
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
     * Execute the controler of the "search" view
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
                                                      'id_node','description','rewrite_name') ));  

        // set session var with text to highlight
		  $this->setHighlightText();

        // get node + node branch of each article
        foreach($this->viewVar['articles'] as & $article)
        {
            $article['nodeBranch'] = array();
            $article['node']       = array();
            
            // get navigation node branch content of the article node
            $this->model->action('navigation','getBranch', 
                             array('result'  => & $article['nodeBranch'],
                                   'id_node' => (int)$article['id_node'],
                                   'fields'  => array('title','id_node','id_parent','rewrite_name')));   
                                   
            // get article node content
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $article['node'],
                                       'id_node' => (int)$article['id_node'],
                                       'fields'  => array('title','id_node','rewrite_name')));
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
                                   'url_postfix' => '#result',
                                   'var_prefix' => 'search_',
                                   'css_class'  => 'search_pager'));    
                               
        // get result of the header and footer controller
        // 
        $this->viewVar['header']      = $this->controllerLoader->header(); 
        $this->viewVar['footer']      = $this->controllerLoader->footer();  
        $this->viewVar['rightBorder'] = $this->controllerLoader->rightBorder();      
    }
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        $this->searchString = $this->httpRequest->getParameter( 'search', 'request', 'raw' );
        
        // create cache id if cache enabled
        // here we use the article id as a unique cache id for this controller
        if($this->cacheExpire > 0)
        {
            $this->cacheId = 'search'.$this->searchString;
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
            $this->viewVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->viewVar['isUserLogged'] = TRUE;
        }
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');     
        
        if( ($this->viewVar['isUserLogged'] == TRUE) && ($this->viewVar['loggedUserRole'] < 100) )
        {
            $this->cacheExpire = 0;
            $this->viewVar['showEditLink'] = TRUE; 
        }
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
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
        
        $search_page = (int) $this->httpRequest->getParameter( 'search_page', 'get', 'digits' );
        // get current article pager page
        if($search_page === false)
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$search_page;
        }
        
        // view vars
        $this->viewVar['charset'] = $this->config->getModuleVar('common', 'charset');
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller');        
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/'.$this->config->getModuleVar('common', 'styles_folder');
        $this->viewVar['urlBase']   = $this->router->getBase();
    }
    
    /**
     * set session var with text to highlight
     *
     */    
    private function setHighlightText()
    {
    		$text = explode(" ",$this->searchString);
    		$_text = array();
    		foreach($text as $str)
    		{
    			$_text[] = str_replace(array("+","-","*"),array("","",""),$str);
    		}
    		$this->model->session->set('TextHighlight', serialize($_text));
    }
}

?>