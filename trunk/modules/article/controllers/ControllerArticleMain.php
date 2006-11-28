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
 
class ControllerArticleMain extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
   /**
     * current id_node
     * @var int $current_id_node
     */
    private $current_id_node;    
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init variables for this view
        $this->initVars();

        $id_article_up = $this->httpRequest->getParameter('id_article_up', 'get', 'int');
        $id_article_down = $this->httpRequest->getParameter('id_article_down', 'get', 'int');

        // move up or down a node
        if( !empty($id_article_up) &&
            ($this->allowModify() == TRUE) )
        {
            $this->model->action('article','moveArticleRank', 
                                 array('id_article' => (int)$id_article_up,
                                       'id_node'    => (int)$this->current_id_node,
                                       'dir'        => 'up'));        
        }
        elseif( !empty($id_article_down) &&
                ($this->allowModify() == TRUE) )
        {
            $this->model->action('article','moveArticleRank', 
                                 array('id_article' => (int)$id_article_down,
                                       'id_node'    => (int)$this->current_id_node,
                                       'dir'        => 'down'));        
        }
        
        // get current node data if we arent at the top level node
        if($this->current_id_node != 0)
        {
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $this->viewVar['node'],
                                       'id_node' => (int)$this->current_id_node,
                                       'error'   => & $this->viewVar['error'],
                                       'fields'  => array('title','id_node')));        
        }
    
        // get child navigation nodes
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->viewVar['nodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node','id_parent',
                                                      'status')));
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));  
                                   
        // get node related articles
        $this->model->action('article','getNodeArticles', 
                             array('result'  => & $this->viewVar['articles'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'order'   => $this->order,
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),                                   
                                   'fields'  => array('title','id_article','status',
                                                      'pubdate','modifydate')));                                   

        // if author is logged check if he has access to edit articles
        $this->assignArticleRights();

        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->viewVar['pager'],
                                   'id_node'    => array((int)$this->current_id_node),
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => $this->pagerUrl,
                                   'var_prefix' => 'article_',
                                   'css_class'  => 'smart_pager'));  


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
        $this->current_id_node = $this->httpRequest->getParameter('id_node', 'request', 'int');
        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $this->current_id_node ) 
        {
            $this->viewVar['id_node']  = 0;
            $this->current_id_node    = 0;      
        }
        else
        {
            $this->viewVar['id_node']  = (int)$this->current_id_node;       
        }    

        // set template variable to show edit links        
        $this->viewVar['showArticle'] = $this->allowModify();       

        if($this->current_id_node == 0)
        {
            $this->viewVar['showAddArticle'] = FALSE;        
        }
        else
        {
            $this->viewVar['showAddArticle'] = TRUE;
        }
        
        // template variables
        //
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the child nodes
        $this->viewVar['nodes']  = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // data of the node articles
        $this->viewVar['articles'] = array();  
        // pager links
        $this->viewVar['pager'] = '';
        // errors
        $this->viewVar['error']  = FALSE;   

        $order = $this->httpRequest->getParameter('order', 'post', 'alpha');
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
            $this->order = array($this->model->config['article']['default_order'],
                                 $this->model->config['article']['default_ordertype']);
            $this->viewVar['order'] = $this->model->config['article']['default_order'];
            $this->viewVar['ordertype'] = $this->model->config['article']['default_ordertype'];
            $this->model->session->set('article_order', 
                                       $this->model->config['article']['default_order']);
            $this->model->session->set('ordertype', 
                                       $this->model->config['article']['default_ordertype']);
        }

        // set articles limit per page
        $this->articlesPerPage = 15;

        $article_page = $this->httpRequest->getParameter('article_page', 'get', 'int');
        
        // get current article pager page
        if(!empty($article_page))
        {
            $this->pageNumber = (int)$article_page;
            $this->viewVar['article_page'] = (int)$article_page;
            $this->model->session->set('article_page', (int)$article_page);        
        }
        elseif(NULL !== ($article_page = $this->model->session->get('article_page')))
        {
            $this->pageNumber = $article_page;
            $this->viewVar['article_page'] = $article_page;
        }        
        else
        {
            $this->pageNumber = 1;
            $this->viewVar['article_page'] = 1;
            $this->model->session->set('article_page', 1);
        } 
        
        // The url passed to the pager action
        $this->pagerUrl = $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/article/id_node/'.$this->current_id_node;    
    }
    
     /**
     * if author (60) is logged assign rights to edit articles
     *
     */      
    private function assignArticleRights()
    {
        foreach($this->viewVar['articles'] as &$article)
        {
            // if author is logged check if he has access to edit articles
            if($this->controllerVar['loggedUserRole'] < 60 )
            {
                $article['hasAccess'] = true;
                continue;
            }
            $article['hasAccess'] = $this->model->action('article','checkUserRights',
                                        array('id_article' => (int)$article['id_article'],
                                              'id_user'    => (int)$this->controllerVar['loggedUserId']));

             
        }
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