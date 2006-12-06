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
 * ControllerSimpleNodeArticle class
 */

class ControllerSimpleNodeArticle extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the controller of the "simpleNodeArticle" view
     */
    function perform()
    {     
        $this->initVars();

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
        // $this->model->action( 'common', 'filterDisableBrowserCache');    

        $this->current_id_node    = $this->httpRequest->getParameter( 'id_node', 'request', 'digits' );
             
        if( ($this->current_id_node === false) ) 
        {
              $this->router->redirect(); 
        }

        $node_article = array();
        // we need only the first article in this node                                                    
        $this->model->action('article','getNodeArticles',
                                 array('id_node' => (int)$this->current_id_node,
                                       'result'  => & $node_article,
                                       'status'  => array('>=', 4),
                                       'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                       'order'   => array('rank', 'asc'),
                                       'limit'   => array('perPage' => 1,
                                                          'numPage' => 0),
                                       'fields'  => array('id_article') ));
            
        if(!isset($node_article[0]['id_article']))
        {
            $this->router->redirect();
        }
            
        $this->current_id_article = (int)$node_article[0]['id_article'];
      
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
        $this->viewVar['article'] = array();
        $this->viewVar['articleFiles'] = array();

        // init template variable for keyword related articles
        $this->viewVar['keywordArticle'] = array();
        // init template variable for keyword related links
        $this->viewVar['keywordLink'] = array(); 
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = $this->config->getModuleVar('common', 'charset');
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/'.$this->config->getModuleVar('common', 'styles_folder');
        $this->viewVar['urlBase'] = $this->router->getBase();   
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];
        $this->viewVar['isUserLogged']       = $this->viewVar['isUserLogged'];
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller'); 
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
}

?>