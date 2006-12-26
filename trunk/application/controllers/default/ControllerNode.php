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

class ControllerNode extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
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

         // get requested node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','body','id_node',
                                                    'media_folder','logo','rewrite_name')));
         
        // get child nodes content of the requested node
        // only with status=2, means active      
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->viewVar['childNodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','short_text','id_node',
                                                      'rewrite_name')));
 
        // get navigation node branch content of the requested node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['nodeBranch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','id_node','rewrite_name')));  
                                 
        // get node attached files
        $this->model->action('navigation','getAllFiles',
                             array('result'  => & $this->viewVar['nodeFiles'],
                                   'id_node' => (int)$this->current_id_node,
                                   'order'   => 'rank',
                                   'fields'  => array('id_file',
                                                      'file',
                                                      'size',
                                                      'mime',
                                                      'title',
                                                      'description')) );   

        // get node related article titles count by 10                                                     
        $this->model->action('article','getNodeArticles',
                             array('id_node' => (int)$this->current_id_node,
                                   'result'  => & $this->viewVar['nodeArticles'],
                                   'status'  => array('>=', 4),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),
                                   'order'   => array('rank', 'asc'),
                                   'limit'   => array('perPage' => (int)$this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),
                                   'fields'  => array('id_article','title',
                                                      'pubdate','modifydate','rewrite_name') ));

        // get node related links
        $this->model->action('link','getLinks', 
                             array('result'  => & $this->viewVar['links'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('=','2'),
                                   'fields'  => array('title','url','id_link',
                                                      'description')));   

        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->viewVar['pager'],
                                   'id_node'    => array((int)$this->current_id_node),
                                   'status'     => array('>=', '4'),
                                   'pubdate'    => array('<=', 'CURRENT_TIMESTAMP'),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 5,
                                   'url'        => $this->viewVar['urlBase'].'/id_node/'.$this->current_id_node,
                                   'url_postfix' => '#article',
                                   'var_prefix' => 'article_',
                                   'css_class'  => 'op_pager'));  

        // get node related keywords
        $keywords = array();
        $this->model->action('navigation','getKeywordIds', 
                             array('result'     => & $keywords,
                                   'id_node'    => (int)$this->current_id_node,
                                   'key_status' => array('=', 2) ));     

        // if there are node related keywords, 
        if(count($keywords) > 0)
        { 
            // get links which have the same keywords as the current node
            $this->model->action('link','fromKeyword',
                                 array('id_key_list' => & $keywords,
                                       'result'      => & $this->viewVar['keywordLink'],
                                       'status'      => array('=', 2),
                                       'fields'      => array('id_link','url','title','description') )); 
        }
        
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
        
        if( ($this->viewVar['isUserLogged'] == TRUE) && ($this->viewVar['loggedUserRole'] < 100) )
        {
            $this->cacheExpire = 0;
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
        
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        $this->current_id_node = (int) $this->httpRequest->getParameter( 'id_node', 'get', 'digits' );
          
        if( ($this->current_id_node === false) ) 
        {
              $this->router->redirect();
        }

         $add = $this->httpRequest->getParameter( 'add', 'get', 'digits' );
			if($add !== false)
			{
				$this->model->action('tools','addRandomContent');
			}
        
        // create cache id if cache enabled
        // here we use the node id as a unique cache id for this controller
        if($this->cacheExpire > 0)
        {
            $this->cacheId = 'node'.$this->current_id_node;
        }
        
        // check if the demanded node has at least status 2
        $nodeStatus = $this->model->action('navigation','getNodeStatus', 
                                            array('id_node' => $this->current_id_node));  
        
        // if the requested node isnt active
        if( $nodeStatus < 2 )
        {
            $this->router->redirect();
        } 
        // if the requested node is only available for registered users
        elseif( ($nodeStatus == 3) && ($this->viewVar['isUserLogged'] == FALSE) )
        {
              // set url vars to come back to this page after login
              $this->model->session->set('url','id_node/'.$this->current_id_node);
              // switch to the login page
              $this->router->redirect( 'cntr/login' ); 
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
        $this->viewVar['nodeArticles'] = array();
        $this->viewVar['links']        = array();
        $this->viewVar['keywordLink'] = array();
        $this->viewVar['pager']        = '';

        // set articles limit per page
        $article_page = (int) $this->httpRequest->getParameter( 'article_page', 'get', 'digits' );
        $this->articlesPerPage = 10;
        // get current article pager page
        if($article_page === null)
        {
            $this->pageNumber = 1;
        }
        else
        {
            $this->pageNumber = (int)$article_page;
        }
        
        // init view vars
        $this->viewVar['charset'] = $this->config->getModuleVar('common', 'charset');
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller');        
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/'.$this->config->getModuleVar('common', 'styles_folder');
        $this->viewVar['urlBase']   = $this->router->getBase();
    }
}

?>