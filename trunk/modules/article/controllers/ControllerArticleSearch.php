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
 * ControllerArticleMain
 *
 */
 
class ControllerArticleSearch extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
      
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();

        // search articles                                                   
        $this->model->action('article','search',
                             array('result'  => & $this->viewVar['articles'], 
                                   'search'  => (string)$this->searchString,
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),                                   
                                   'fields'  => array('id_article','title','status',
                                                      'id_node','pubdate','modifydate') )); 

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
                                       
            // if author is logged check if he has access to edit articles
            $this->assignArticleRights( $article );
        }
                                   
        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->viewVar['pager'],
                                   'search'     => (string)$this->searchString,
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => $this->pagerUrl,
                                   'var_prefix' => 'search_',
                                   'css_class'  => 'search_pager'));          

        // get article locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each article
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->viewVar['articles'] as $article)
        {
            // lock the user to edit
            $result = $this->model->action('article','lock',
                                     array('job'        => 'is_locked',
                                           'id_article' => (int)$article['id_article'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->viewVar['articles'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->viewVar['articles'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $post_search = $this->httpRequest->getParameter('search', 'post', 'raw');
        $get_search  = $this->httpRequest->getParameter('search', 'get', 'raw');
        
        if( !empty($post_search) )
        {
            $this->searchString = JapaCommonUtil::stripSlashes((string)$post_search);
            $this->pagerUrlSearchString = urlencode(JapaCommonUtil::stripSlashes((string)$post_search));
        }
        elseif( !empty($get_search) )
        {
            $this->searchString = urldecode(JapaCommonUtil::stripSlashes((string)$get_search));
            $this->pagerUrlSearchString = JapaCommonUtil::stripSlashes((string)$get_search);
        }        
        else
        {
            $this->searchString = '';
            $this->pagerUrlSearchString = '';
        }
        
        // assign template variable with search string
        $this->viewVar['search'] = & $this->searchString;
        
        // template array variables
        $this->viewVar['articles'] = array();
        $this->viewVar['pager']    = '';

        // set articles limit per page
        $this->articlesPerPage = 15;
        
        $search_page  = $this->httpRequest->getParameter('search_page', 'get', 'int');       
        
        // get current article pager page
        if(!empty($search_page))
        {
            $this->pageNumber = (int)$search_page;
            $this->viewVar['search_page'] = (int)$search_page;
            $this->model->session->set('article_page', (int)$search_page);        
        }
        elseif(NULL !== ($search_page = $this->model->session->get('search_page')))
        {
            $this->pageNumber = $search_page;
            $this->viewVar['search_page'] = $search_page;
        }        
        else
        {
            $this->pageNumber = 1;
            $this->viewVar['search_page'] = 1;
            $this->model->session->set('search_page', 1);
        } 
        
        // The url passed to the pager action
        $this->pagerUrl = $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/nodecoration/1/mod/article/cntr/search/search/'.$this->pagerUrlSearchString;    

        $order     = $this->httpRequest->getParameter('order', 'post', 'raw');
        $ordertype = $this->httpRequest->getParameter('ordertype', 'post', 'alpha');   
             
        // set article order
        if(!empty($order))
        {
            $this->order = array((string)$order,(string)$ordertype);
            $this->viewVar['order'] = (string)$order; 
            $this->viewVar['ordertype'] = (string)$ordertype;
            $this->model->session->set('article_order', (string)$order);
            $this->model->session->set('ordertype', (string)$ordertype);
            $this->model->session->del('article_page');
        }
        elseif(NULL !== ($order = $this->model->session->get('article_order')))
        {
            $ordertype = $this->model->session->get('ordertype');
            $this->order = array($order,$ordertype);
            $this->viewVar['order'] = $order;
            $this->viewVar['ordertype'] = (string)$ordertype;
        }        
        else
        {
            $this->order = array($this->config->getModuleVar('article','default_order'),
                                 $this->config->getModuleVar('article','default_ordertype'));
            $this->viewVar['order'] = $this->config->getModuleVar('article','default_order');
            $this->viewVar['ordertype'] = $this->config->getModuleVar('article','default_ordertype');
            $this->model->session->set('article_order', 
                                       $this->config->getModuleVar('article','default_order'));
            $this->model->session->set('ordertype', 
                                       $this->config->getModuleVar('article','default_ordertype'));
        }
    }
    
     /**
     * if author (60) is logged assign rights to edit articles
     *
     * @param array $article
     */      
    private function assignArticleRights( & $article )
    {
        // if author is logged check if he has access to edit articles
        if($this->controllerVar['loggedUserRole'] < 60 )
        {
            $article['hasAccess'] = true;
            return;
        }
        $article['hasAccess'] = $this->model->action('article','checkUserRights',
                                    array('id_article' => (int)$article['id_article'],
                                          'id_user'    => (int)$this->controllerVar['loggedUserId']));
    }    
    
     /**
     * has the logged user the rights to modify?
     * at least edit (60) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] < 100 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>